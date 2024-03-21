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
 * Housekeeping controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Housekeeping extends Controller
{
    /**
     * Holds the company bean.
     */
    private $company = null;

    /**
     * Housekeeping.
     *
     * @return void
     */
    public function index():void
    {
        $this->company = R::load('company', WOBB_COMPANY_ID);
        $this->sendMail();
        error_log('Housekeeeeeeeping!');
    }

    /**
     * Sends an email to this beans person email address with the dealer invoice pdf attached.
     *
     * @return bool
     */
    public function sendMail()
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        if ($smtp = $this->company->smtp()) {
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
            $mail->Port = $smtp['port'];                          // SMTP port
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
        $mail->setFrom($this->company->emailnoreply, $this->company->legalname);
        $mail->addReplyTo($this->company->email, $this->company->legalname);
        //$mail->addAddress($this->record->person->email, $this->record->person->name);
        $mail->addAddress('info@sah-company.com', 'Stephan Hombergs');
        //$mail->WordWarp = 50;
        $mail->isHTML(true);
        $mail->Subject = 'Housekeeeeeeeping';
        /*
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
        */
        $mail->Body = '<h1>Housekeeeeeeeping</h1>';
        $mail->AltBody = 'k.T.';
        //$attachment = $mpdf->Output('', 'S');
        //$mail->addStringAttachment($attachment, $filename);

        if ($mail->send()) {
            return true;
        } else {
            error_log($mail->ErrorInfo);
            return false;
        }
    }
}
