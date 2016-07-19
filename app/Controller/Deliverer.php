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
 * Deliverer controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Deliverer extends Controller
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
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('deliverer', $id);
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        //Permission::check(Flight::get('user'), 'deliverer', 'index');
        $this->layout = 'index';
        $this->render();
    }
    
    /**
     * Generates the invoice PDF for dealer audience.
     *
     * @uses generatePDF()
     * @return void
     */
    public function dealer()
    {
        $this->layout = 'dealer';
        $filename = I18n::__('deliverer_dealer_invoice_filename', null, 
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__('deliverer_dealer_invoice_docname', null, 
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * Generates the invoice PDF for dealer audience and mails it.
     *
     * @uses generatePDF()
     * @return void
     */
    public function mail()
    {
        $this->layout = 'dealer';
        $filename = I18n::__('deliverer_dealer_invoice_filename', null, 
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__('deliverer_dealer_invoice_docname', null, 
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        if ( $this->sendMail( $filename, $docname, $mpdf ) ) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__('deliverer_send_mail_success'));            
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__('deliverer_send_mail_failed'), 'warning');
        }
        R::store($this->record);
        $this->redirect(sprintf('/purchase/calculation/%d', $this->record->csb->getId()));
    }
    
    /**
     * Generates the service PDF for dealer audience.
     *
     * @uses generatePDF()
     * @return void
     */
    public function service()
    {
        $this->layout = 'service';
        $filename = I18n::__('deliverer_dealer_service_filename', null, 
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__('deliverer_dealer_service_docname', null, 
            array(
                $this->record->invoice->name
            )
        );
        $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Generates the invoice PDF for company's internal archive process.
     *
     * @uses generatePDF()
     * @return void
     */
    public function internal()
    {
        $this->layout = 'internal';
        $filename = I18n::__('deliverer_company_invoice_filename', null, 
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__('deliverer_company_invoice_docname', null, 
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * Sends an email to this beans person email address with the dealer invoice pdf attached.
     *
     * @param string $filename
     * @param string $docname
     * @param mPDF $mpdf
     */
    public function sendMail( $filename, $docname, $mpdf )
    {
        $mail = new PHPMailer();
        
        if ( $smtp = $this->record->invoice->company->smtp() ) {
            $mail->SMTPDebug = 1;                                 // Set debug mode, 1 = err/msg, 2 = msg
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smtp['host'];                          // Specify main and backup server
            if ( $smtp['auth'] ) {
                $mail->SMTPAuth = true;                           // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false;                          // Disable SMTP authentication
            }
            $mail->Username = $smtp['user'];                      // SMTP username
            $mail->Password = $smtp['password'];                  // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        }
        
        $mail->CharSet = 'UTF-8';
        $mail->From = $this->record->invoice->company->emailnoreply;
        $mail->FromName = $this->record->invoice->company->legalname;
        $mail->addAddress( $this->record->person->email, $this->record->person->name );
        $mail->WordWarp = 50;
        $mail->isHTML( true );
        $mail->Subject = $docname;
        
        ob_start();
        Flight::render('deliverer/mail/html', array(
            'record' => $this->record
        ));
        $html = ob_get_clean();
        ob_start();
        Flight::render('deliverer/mail/text', array(
            'record' => $this->record
        ));
        $text = ob_get_clean();
        $mail->Body = $html;
        $mail->AltBody = $text;
        $attachment = $mpdf->Output('', 'S');
        
        $mail->addStringAttachment( $attachment, $filename );
        
        return $mail->send();
    }
    
    /**
     * Generates an PDF using mPDF library and return the mPDF object.
     *
     * @param string $docname defaults to 'invoice'
     * @return mPDF $mpdf
     */
    private function generatePDF($docname = 'invoice')
    {
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->record->invoice->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('deliverer/' . $this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'conditions' => $this->record->person->withCondition(" ( doesnotaffectinvoice IS NULL OR doesnotaffectinvoice = 0 ) ")->ownCondition,
            'costs' => $this->record->person->ownCost, 
            'specialprices' => $this->record->with(" ORDER BY kind, piggery DESC ")->ownSpecialprice,
            'nonqs' => false,
            'bookingdate' => $this->record->invoice->localizedDate('bookingdate'),
            'pubdate' => $this->record->csb->localizedDate('pubdate'),
            'title' => I18n::__("deliverer_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts       
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        return $mpdf;
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
        Flight::render('deliverer/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('deliverer/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("deliverer_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
