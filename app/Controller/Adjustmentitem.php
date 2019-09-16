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
class Controller_Adjustmentitem extends Controller_Deliverer
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
		
		$this->records = R::find('adjustmentitem', "person_id=:pid AND adjustment_id=:adid ORDER BY invoice_id", array(
			':pid' => $this->record->person->getId(),
			':adid' => $this->record->adjustment->getId()
		));
		
		$total = R::getCell("SELECT ROUND(SUM(gros), 2) AS total FROM adjustmentitem WHERE person_id=:pid AND adjustment_id=:adid", array(
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
