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
        $filename = I18n::__(
            'deliverer_dealer_invoice_filename',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__(
            'deliverer_dealer_invoice_docname',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $this->record->pdfStateDealer++;
        R::store($this->record);
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
        //$this->redirect(sprintf('/purchase/calculation/%d/#deli-%d', $this->record->csb->getId(), $this->record->getId()));
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
        $filename = I18n::__(
            'deliverer_dealer_invoice_filename',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__(
            'deliverer_dealer_invoice_docname',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        if ($this->sendMail($filename, $docname, $mpdf)) {
            $this->record->sent = true;
            Flight::get('user')->notify(I18n::__('deliverer_send_mail_success'));
        } else {
            $this->record->sent = false;
            Flight::get('user')->notify(I18n::__('deliverer_send_mail_failed'), 'warning');
        }
        R::store($this->record);
        $this->redirect(sprintf('/purchase/calculation/%d/#deli-%d', $this->record->csb->getId(), $this->record->getId()));
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
        $filename = I18n::__(
            'deliverer_company_invoice_filename',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__(
            'deliverer_company_invoice_docname',
            null,
            array(
                $this->record->invoice->name
            )
        );
        $this->record->pdfStateInternal++;
        R::store($this->record);
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * Export the person list as .csv file
     *
     * @return void
     */
    public function csv()
    {
        $company = R::load('company', WOBB_COMPANY_ID);
        $filename = I18n::__('person_filename_csv', null, [$company->legalname]);
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
            I18n::__('person_csv_nickname'),
            I18n::__('person_csv_account'),
            I18n::__('person_csv_vvvo'),
            I18n::__('person_csv_name'),
            I18n::__('person_csv_email'),
            I18n::__('person_csv_postaladdress'),
            I18n::__('person_csv_relsprice'),
            I18n::__('person_csv_reldprice')
        ];
        $csv->heading = true;
        $csv->data = $this->makeCsvData();
        $csv->output($filename);
    }

    /**
     * Generates the array for csv export
     */
    public function makeCsvData()
    {
        $sql = "SELECT p.nickname AS nickname, p.account AS account, p.vvvo AS vvvo, REPLACE(p.name, '\r\n', ' ') AS name, p.email AS email, CONCAT(adr.street, ', ', adr.zip, ' ', adr.city) AS postaladdress, FORMAT(relsprice, 3, 'de_DE') AS relsprice, FORMAT(reldprice, 3, 'de_DE') AS reldprice FROM person AS p LEFT JOIN address AS adr ON adr.person_id = p.id AND adr.label = 'billing' WHERE enabled = 1 ORDER BY p.nickname";
        return R::getAll($sql);
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

        if ($smtp = $this->record->invoice->company->smtp()) {
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
        $mail->setFrom($this->record->invoice->company->emailnoreply, $this->record->invoice->company->legalname);
        $mail->addReplyTo($this->record->invoice->company->email, $this->record->invoice->company->legalname);
        $mail->addAddress($this->record->person->email, $this->record->person->name);
        $mail->WordWarp = 50;
        $mail->isHTML(true);
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
        ob_start();
        Flight::render('deliverer/' . $this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'conditions' => $this->record->ownAppliedcondition,
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
        $mpdf->WriteHTML($html);
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
