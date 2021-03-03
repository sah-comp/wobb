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
 * Adjustmentitem controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Adjustmentitem extends Controller
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
     * Container for the current adjustment bean.
     *
     * @var Model_Csb
     */
    public $record;
    
    /**
     * Container for the current collection of adjustment beans.
     *
     * @var array
     */
    public $records;
    
    /**
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('adjustmentitem', $id);
    }
	
    /**
     * Generates the PDF for aftersales adjustments.
     *
     * @uses generatePDF()
     * @return void
     */
    public function internal()
    {
		//error_log('Gutschrift ' . $this->record->invoice->name . ' als PDF generieren.');
		//return;
        $this->layout = 'internal';
        $filename = I18n::__(
            'adjustmentitem_company_invoice_filename',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__(
            'adjustmentitem_company_invoice_docname',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }
	
    /**
     * Generates the PDF for aftersales adjustments and mails it.
     *
     * @uses generatePDF()
     * @return void
     */
    public function mail()
    {
        $this->layout = 'internal';
        $filename = I18n::__(
            'adjustmentitem_company_invoice_filename',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__(
            'adjustmentitem_company_invoice_docname',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        if ($this->sendMail($filename, $docname, $mpdf)) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__('adjustmentitem_send_mail_success'));
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__('adjustmentitem_send_mail_failed'), 'warning');
        }
        R::store($this->record);
        $this->redirect(sprintf('/adjustment/edit/%d', $this->record->adjustment->getId()));
    }
	
    /**
     * Sends an email to this beans person email address with the dealer invoice pdf attached.
     *
     * @param string $filename
     * @param string $docname
     * @param mPDF $mpdf
     */
    public function sendMail($filename, $docname, $mpdf)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        if ($smtp = $this->record->adjustment->company->smtp()) {
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
        //$mail->From = $this->record->invoice->company->emailnoreply;
        //$mail->FromName = $this->record->invoice->company->legalname;
		$mail->setFrom($this->record->adjustment->company->emailnoreply, $this->record->adjustment->company->legalname);
		$mail->addReplyTo($this->record->adjustment->company->email, $this->record->adjustment->company->legalname);
        $mail->addAddress($this->record->person->email, $this->record->person->name);
        $mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = $docname;

        ob_start();
        Flight::render('adjustmentitem/mail/html', array(
            'record' => $this->record
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('adjustmentitem/mail/text', array(
            'record' => $this->record
        ));
        $text = ob_get_clean();
        $mail->Body = $html;
        $mail->AltBody = $text;
        $attachment = $mpdf->Output('', 'S');

        $mail->addStringAttachment($attachment, $filename);
		
		if ($mail->send()) {
			return true;
		} else {
			error_log($mail->ErrorInfo);
			return false;
		}
    }
	
    /**
     * Generates an PDF using mPDF library and return the mPDF object.
     *
     * @param string $docname defaults to 'invoice'
     * @return mPDF $mpdf
     */
    private function generatePDF($docname = 'invoice')
    {
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->record->invoice->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
		
		$this->records = R::find('adjustmentitem', "person_id=:pid AND adjustment_id=:adid ORDER BY invoice_id", array(
			':pid' => $this->record->person->getId(),
			':adid' => $this->record->adjustment->getId()
		));
		
		$total = R::getCell("SELECT ROUND(SUM(ROUND(gros, 2)), 2) AS total FROM adjustmentitem WHERE person_id=:pid AND adjustment_id=:adid", array(
			':pid' => $this->record->person->getId(),
			':adid' => $this->record->adjustment->getId()				
		));
		
        $templates = Flight::get('templates');
		
        ob_start();
        Flight::render('adjustmentitem/' . $this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
			'total' => $total,
            'bookingdate' => $this->record->invoice->localizedDate('bookingdate'),
			'bookingslot' => strftime($templates['date'], strtotime('next monday', strtotime($this->record->adjustment->pubdate))),
            'title' => I18n::__("deliverer_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        return $mpdf;
    }
    
}
