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
 * Booking controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Booking extends Controller
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
     * Holds the base url
     */
    public  $base_url = '/booking';
    
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
    public $records = array();
    
    /**
     * Container for the totals of the current selection.
     *
     * @var array
     */
    public $totals = array();
    
    /**
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        if ( ! isset($_SESSION['booking'])) {
            $_SESSION['booking'] = array(
                'fy' => Flight::setting()->fiscalyear,
                'lo' => $this->getLowestInvoiceNumber(),
                'hi' => $this->getHighestInvoiceNumber(),
                'deliverer' => null
                
            );
        }
        $this->record = R::load('invoice', $id);
    }
    
    /**
     * Clear the filter and start over.
     */
    public function clearfilter()
    {
        Permission::check(Flight::get('user'), 'invoice', 'index');
        unset($_SESSION['booking']);
        $this->redirect('/booking/index');
    }
    
    /**
     * Returns the lowest invoice number of the current fiscal year.
     *
     * @return int
     */
    public function getLowestInvoiceNumber()
    {
        return R::getCell(" SELECT min(name) FROM invoice WHERE fy = ? AND MONTH(dateofslaughter) = MONTH(CURRENT_DATE())", array(
            Flight::setting()->fiscalyear
        ));
    }
    
    /**
     * Returns the highest invoice number of the current fiscal year.
     *
     * @return int
     */
    public function getHighestInvoiceNumber()
    {
        return R::getCell(" SELECT max(name) FROM invoice WHERE fy = ? ", array(
            Flight::setting()->fiscalyear
        ));
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'invoice', 'index');
        $this->layout = 'index';
        if (Flight::request()->method == 'POST') {
            $dialog = Flight::request()->data->dialog;
            $_SESSION['booking'] = array(
                'fy' => $dialog['fy'],
                'lo' => $dialog['lo'],
                'hi' => $dialog['hi']
            );
            Flight::get('user')->notify(I18n::__('booking_select_success'));
            $this->redirect($this->base_url);
        }
        $this->getCollection();
        $this->render();
    }
    
    /**
     * Find all records according to filter settings.
     *
     * @uses $records Will hold the invoice beans according to the set filter
     * @uses $totals Will hold the sums of certain attributes according to the filter
     * @param string $order_dir defaults to 'DESC'
     * @return void
     */
    public function getCollection($order_dir = 'DESC')
    {
        $this->records = R::find('invoice', " fy = :fy AND ( name >= :lo AND name <= :hi ) AND instructed = 0 ORDER BY name " . $order_dir, array(
            ':fy' => $_SESSION['booking']['fy'],
            ':lo' => $_SESSION['booking']['lo'],
            ':hi' => $_SESSION['booking']['hi']
        ));
        $this->totals = R::getRow(" SELECT count(id) AS count, SUM(totalnet) AS totalnet, SUM(subtotalnet) AS subtotalnet, SUM(totalnetnormal) as totalnetnormal, SUM(totalnetfarmer) as totalnetfarmer, SUM(totalnetother) as totalnetother, SUM(vatvalue) AS vatvalue, SUM(totalgros) AS totalgros, SUM(bonusnet) AS bonusnet, SUM(costnet) AS costnet FROM invoice WHERE fy = :fy AND ( name >= :lo AND name <= :hi ) ", array(
            ':fy' => $_SESSION['booking']['fy'],
            ':lo' => $_SESSION['booking']['lo'],
            ':hi' => $_SESSION['booking']['hi']
        ));
        return null;
    }
    
    /**
     * Updates all records according to filter settings as instructed
     *
     * @return void
     */
    public function updateCollection()
    {
        R::exec(" UPDATE invoice SET instructed = 1 WHERE fy = :fy AND ( name >= :lo AND name <= :hi ) ", array(
            ':fy' => $_SESSION['booking']['fy'],
            ':lo' => $_SESSION['booking']['lo'],
            ':hi' => $_SESSION['booking']['hi']
        ));
        return null;
    }
    
    /**
     * Toggle instructed attribute.
     */
    public function instructed()
    {
        if ( $this->record->instructed ) {
            $this->record->instructed = 0;
        } else {
            $this->record->instructed = 1;
        }
        R::store($this->record);
        Flight::render('booking/single', array(
            'record' => $this->record
        ));
    }
    
    /**
     * Generates an PDF with a list of selected bookings using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdflist()
    {
        $this->getCollection('ASC');
        $this->record = reset($this->records);
        $filename = I18n::__('booking_list_filename', null, array(
            $_SESSION['booking']['fy'],
            $_SESSION['booking']['lo'],
            $_SESSION['booking']['hi']
        ));
        $title = I18n::__('booking_list_docname', null, array(
            $_SESSION['booking']['fy'],
            $_SESSION['booking']['lo'],
            $_SESSION['booking']['hi']
        ));
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('booking/print', array(
            'record' => $this->record,
            'records' => $this->records,
            'fy' => $_SESSION['booking']['fy'],
            'lo' => $_SESSION['booking']['lo'],
            'hi' => $_SESSION['booking']['hi'],
            'totals' => $this->totals
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        $mpdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdfbooking()
    {
        $this->getCollection('ASC');
        $this->record = reset($this->records);
        $filename = I18n::__('booking_filename', null, array(
            $_SESSION['booking']['fy'],
            $_SESSION['booking']['lo'],
            $_SESSION['booking']['hi']
        ));
        $title = I18n::__('booking_docname', null, array(
            $_SESSION['booking']['fy'],
            $_SESSION['booking']['lo'],
            $_SESSION['booking']['hi']
        ));
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('booking/booking', array(
            'record' => $this->record,
            'records' => $this->records,
            'fy' => $_SESSION['booking']['fy'],
            'lo' => $_SESSION['booking']['lo'],
            'hi' => $_SESSION['booking']['hi'],
            'totals' => $this->totals
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        $mpdf->Output($filename, 'D');
        $this->updateCollection();
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
        Flight::render('booking/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('booking/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("booking_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
