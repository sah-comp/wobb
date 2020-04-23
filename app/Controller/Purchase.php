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
 * Purchase controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Purchase extends Controller
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
     * The fiscal year.
     *
     * @var int
     */
    public $fiscalyear;

    
    /**
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('csb', $id);
		$this->fiscalyear = Flight::setting()->fiscalyear;
    }
    
    /**
     * Update all csb beans averages.
     */
    public function avgAfterburner()
    {
        $days = R::findAll('csb');
        foreach ($days as $id => $day) {
            $day->calcAverages();
        }
        R::storeAll($days);
    }

    /**
     * Lists all slaughterdays of the current year.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'purchase', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('csb', ' YEAR(pubdate) = :fy ORDER BY pubdate DESC', array(
        	':fy' => $this->fiscalyear
        ));
        $this->render();
    }
    
    /**
     * Deletes the csb bean and all related data like deliverer beans, stock beans and so on
     * if the csb is not yet calculated. Otherwise it will show an error.
     */
    public function drop()
    {
        Permission::check(Flight::get('user'), 'purchase', 'expunge');
        if ( $this->record->wasCalculated()) {
            Flight::get('user')->notify(I18n::__('purchase_day_drop_denied_already_billed'), 'error');
            $this->redirect('/purchase/index');
        }
        R::begin();
        try {
            R::trash($this->record);
            R::commit();
            Flight::get('user')->notify(I18n::__('purchase_day_drop_success'));
            $this->redirect('/purchase/index');
        } catch (Exception $e) {    
            error_log($e);
            //R::rollback();
            Flight::get('user')->notify(I18n::__('purchase_day_drop_error'), 'error');
            $this->redirect('/purchase/index');
        }
    }
    
    /**
     * A new slaughter charge.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'purchase', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            if ( $twin = R::findOne('csb', " pubdate = ? LIMIT 1 ", array($this->record->pubdate)) ) {
                Flight::get('user')->notify(I18n::__('purchase_day_already_stored'), 'warning');
                $this->redirect(sprintf('/purchase/calculation/%d', $twin->getId()));                
            }
            R::begin();
            try {
                R::store($this->record);
                $this->record->importFromCsb();
                R::store($this->record);
                $this->record->checkAliasses();
                $this->record->makeDeliverer();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('purchase_day_add_success'));
                $this->redirect(sprintf('/purchase/calculation/%d', $this->record->getId()));
            }
            catch (Exception_Csbfiledatemismatch $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_day_csbdate_mismatch'), 'error');
                $this->redirect('/purchase/add');
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_day_add_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * Display and edit the stock with damages.
     *
     * @todo this has to be a controller on its own?
     */
    public function stock()
    {
        Permission::check(Flight::get('user'), 'purchase', 'edit');
        $this->layout = 'stock';
        if (Flight::request()->method == 'POST') {
            //$this->record = R::graph(Flight::request()->data->dialog, true);
            $dialog = Flight::request()->data->dialog;
            $stock_list = $dialog['stock'];
            R::begin();
            try {
                $this->record->cntattention++;
                R::store($this->record); //must do this, because otherwise prices dont copy!!
                
                foreach ($stock_list as $id => $stock) {
                    $stock_bean = R::graph($stock);
                    R::store($stock_bean);
                }
                //$this->record->calculation();
                //R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('purchase_stock_edit_success'));
                $this->redirect(sprintf('/purchase/calculation/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_stock_edit_error'), 'error');
            }
        }
        
        $this->records = $this->record->getStockThatNeedsAttention();
        $this->render();
    }
    
    /**
     * Calculates prices of the current slaughter charge.
     *
     * @todo this has to be a controller on its own?
     */
    public function calculation()
    {
        Permission::check(Flight::get('user'), 'purchase', 'edit');
        $this->layout = 'calculation';
        if ( ( $count_attention = $this->record->hasStockThatNeedsAttention() ) 
                                                            && $this->record->cntattention < 3 ) {
            Flight::get('user')->notify(I18n::__('purchase_stock_needs_your_attention_again', null, array($count_attention)), 'warning');
            $this->redirect(sprintf('/purchase/stock/%d', $this->record->getId()));
        }
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record); //must do this, because otherwise prices dont copy!!
                $this->record->calculation();
                R::store($this->record);
                $this->record->billing();
                R::store($this->record);
                // check for lanuv and analysis bean which now have to be considered dirty
                $this->record->markInvolvedLanuvAsDirty()
                             ->markInvolvedAnalysisAsDirty();
                R::commit();
                Flight::get('user')->notify(I18n::__('purchase_calculation_edit_success'));
                $this->redirect(sprintf('/purchase/calculation/%d', $this->record->getId()));
            }
            catch (Exception_Missingpricemask $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('calculation_missingpricemask', null, array($e->getMessage())), 'error');
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_calculation_edit_error'), 'error');
            }
        }
        $this->render();
    }
	
    /**
     * Creates a CSV file as required for iQAgrar and tries to send it to
     * the iQAgrar email address which is defined in company.
     */
    public function iqagrar()
    {
        Permission::check(Flight::get('user'), 'purchase', 'edit');
		
		$filename = I18n::__('iqagrar_csv_filename', null, [$this->record->company->ident, $this->record->pubdate]);
		$docname = I18n::__('iqagrar_csv_docname', null, [$this->record->company->ident, $this->record->pubdate]);

		$ads = $this->record->generateADS();
		$check = file_put_contents(Flight::get('upload_dir') . '/' . $filename, $ads, LOCK_EX);

        if ($check !== false && $this->sendIqagrarAsMail($filename, $docname)) {
            $this->record->iqagrarsent = true;
            Flight::get('user')->notify(I18n::__('iqagrar_send_mail_success'));
        } else {
            $this->record->iqagrarsent = false;
            Flight::get('user')->notify(I18n::__('iqagrar_send_mail_failed'), 'warning');
        }
        R::store($this->record);
		
        $this->redirect(sprintf('/purchase/calculation/%d', $this->record->getId()));
    }
	
    /**
     * Sends an email to iQAgrar email address with the CSV file attached.
     *
     * @param string $filename
     * @param string $docname
     */
    public function sendIqagrarAsMail($filename, $docname)
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
        $mail->addAddress($this->record->company->iqagraremail, I18n::__('iqagrar_mail_name'));
		
        $mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('purchase/mail/html', array(
            'record' => $this->record
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('purchase/mail/text', array(
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
     * Renders the current layout.
     */
    protected function render()
    {
        Flight::render('shared/notification', array(), 'notification');
	    //
        Flight::render('shared/navigation/account', array(), 'navigation_account');
		Flight::render('shared/navigation/main', array(), 'navigation_main');
        Flight::render('shared/navigation', array(), 'navigation');
        Flight::render('purchase/toolbar', ['record' => $this->record], 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('purchase/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'fiscalyear' => $this->fiscalyear
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("purchase_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
