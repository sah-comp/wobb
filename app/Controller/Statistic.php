<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Statistic controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Statistic extends Controller
{
    /**
      * Container for javascripts to load.
      *
      * @var array
      */
    public $javascripts = array(
        '/js/tk'
    );
    
    /**
     * Holds the layout to render.
     *
     * @var string
     */
    public $layout = 'index';
    
    /**
     * Container for the current csb bean.
     *
     * @var Model_Csb
     */
    public $record;
    
    /**
     * Container for the current collection of csb beans.
     *
     * @var array
     */
    public $records;
	
	/**
	 * Contains the first year of LANUV stats.
	 *
	 * @var int
	 */
	public $first_year;
	
	/**
	 * Contains the last year of LANUV stats.
	 *
	 * @var int
	 */
	public $last_year;
    
    /**
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('lanuv', $id);
		$this->first_year = R::getCell("SELECT MIN(YEAR(startdate)) AS fy FROM lanuv");
		$this->last_year = R::getCell("SELECT MAX(YEAR(enddate)) AS fy FROM lanuv");
    }

    /**
     * A new lanuv statistic.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'statistic', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_add_success'));
                $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_add_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $startdate = $this->record->localizedDate('startdate');
        $enddate = $this->record->localizedDate('enddate');
        $filename = I18n::__('lanuv_filename', null, array($startdate));
        $title = I18n::__('lanuv_docname', null, array($startdate));
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('statistic/print', array(
            'record' => $this->record,
            'startdate' => $startdate,
            'enddate' => $enddate
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        $mpdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * A certain lanuv summary.
     */
    public function lanuv()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
        $this->layout = 'lanuv';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                $this->record->dirty = false;
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_edit_success'));
                $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_edit_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * Export stock of the lanuv bean as csv for usage with Excel.
     */
    public function weekascsv()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
        $this->record->exportWeekAsCsv();
        exit;
    }
    
    /**
     * Creates a CSV file as required for LANUV and tries to send it to
     * the lanuv email address which is defined in settings.
     */
    public function mail()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
		$filename = I18n::__('lanuv_csv_filename', null, array($this->record->weekOfYear()));
		$docname = I18n::__('lanuv_csv_docname', null, array($this->record->weekOfYear()));

		$csv = $this->record->exportAsCsv();
		$csv->save(Flight::get('upload_dir') . '/' . $filename);
		
        if ($this->sendMail($filename, $docname, $csv)) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__('lanuv_send_mail_success'));
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__('lanuv_send_mail_failed'), 'warning');
        }
        R::store($this->record);
        $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
    }
	
    /**
     * Creates a CSV file as required for LANUV and tries to download it to the client.
     */
    public function download()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
		$filename = I18n::__('lanuv_csv_filename', null, array($this->record->weekOfYear()));
		//$docname = I18n::__('lanuv_csv_docname', null, array($this->record->weekOfYear()));

		$csv = $this->record->exportAsCsv();
		$csv->output($filename);
		exit;
    }
	
    /**
     * Sends an email to LANUV email address with the LANUV stats as CSV file attached.
     *
     * @param string $filename
     * @param string $docname
     * @param mPDF $mpdf
     */
    public function sendMail($filename, $docname, $csv)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        if ($smtp = $this->record->company->smtp()) {
            $mail->SMTPDebug = 4;                                 // Set debug mode, 1 = err/msg, 2 = msg
			/**
			 * uncomment this block to get verbose error logging in your error log file
			 */
			/*
			$mail->Debugoutput = function($str, $level) {
				error_log("debug level $level; message: $str");
			};
			*/
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtp['host'];                          // Specify main and backup server
            if ($smtp['auth']) {
                $mail->SMTPAuth = true;                           // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false;                          // Disable SMTP authentication
            }
			$mail->Port = $smtp['port'];						  // SMTP port
            $mail->Username = $smtp['user'];                      // SMTP username
            $mail->Password = $smtp['password'];                  // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
			
			/**
			 * @see https://stackoverflow.com/questions/30371910/phpmailer-generates-php-warning-stream-socket-enable-crypto-peer-certificate
			 */
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
        }

        $mail->CharSet = 'UTF-8';
		$mail->setFrom($this->record->company->emailnoreply, $this->record->company->legalname);
		$mail->addReplyTo($this->record->company->email, $this->record->company->legalname);
        $mail->addAddress($this->record->company->lanuvemail, I18n::__('lanuv_mail_name'));
		
        $mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('statistic/mail/html', array(
            'record' => $this->record
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('statistic/mail/text', array(
            'record' => $this->record
        ));
        $text = ob_get_clean();
        $mail->Body = $html;
        $mail->AltBody = $text;

        $mail->addAttachment(Flight::get('upload_dir') . '/' . $filename, $filename);
		
		if ($mail->send()) {
			return true;
		} else {
			error_log($mail->ErrorInfo);
			return false;
		}
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'statistic', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('lanuv', ' ORDER BY id DESC');
        if (Flight::request()->method == 'POST') {
            //try to create a new csb bean with data from form
            //which imports the csb data to stock
            //from given stock
            //create each deliverer with its subdeliverers
            //spread data like prices and such
            //if all went well goto /purchase/day/n with given new bean
            //or generate a error notification and stay here
            Flight::get('user')->notify(I18n::__('lanuv_select_success'));
        }
        $this->render();
    }
    
    /**
     * Renders the current layout.
     */
    protected function render()
    {
        Flight::render('shared/notification', array(), 'notification');
	    //
        Flight::render('shared/navigation/account', array(), 'navigation_account');
		Flight::render('shared/navigation/main', array(), 'navigation_main');
        Flight::render('shared/navigation', array(), 'navigation');
        Flight::render('statistic/toolbar', array(
            'record' => $this->record
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('statistic/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
			'first_year' => $this->first_year,
			'last_year' => $this->last_year
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("statistic_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
