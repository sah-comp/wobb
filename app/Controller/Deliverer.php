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
     * Generates an invoice PDF using mPDF library and downloads it to the client.
     */
    public function internal()
    {
        //Permission::check(Flight::get('user'), 'deliverer', 'index');

        $filename = I18n::__('deliverer_invoice_filename', null, 
            array(
                $this->record->invoice->name
            )
        );
        $docname = I18n::__('deliverer_invoice_docname', null, 
            array(
                $this->record->invoice->name
            )
        );
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor($this->record->invoice->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        $this->layout = 'internal';
        ob_start();
        Flight::render('deliverer/' . $this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'conditions' => $this->record->person->ownCondition,
            'costs' => $this->record->person->ownCost, 
            'specialprices' => $this->record->with(" ORDER BY kind, piggery DESC ")->ownSpecialprice,
            'nonqs' => ( $this->record->piggery - $this->record->qspiggery ),
            'stocks' => R::find('stock', " billnumber = ? ORDER BY earmark, mfa DESC, weight DESC", array($this->record->invoice->name)),
            'bookingdate' => $this->record->invoice->localizedDate('bookingdate'),
            'title' => I18n::__("deliverer_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts       
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        $mpdf->Output($filename, 'D');
        exit;
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
