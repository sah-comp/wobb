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
 * Invoice controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Invoice extends Controller
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
    public $base_url = '/invoice';

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
        if (! isset($_SESSION['invoice'])) {
            $_SESSION['invoice'] = array(
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
        unset($_SESSION['invoice']);
        $this->redirect('/invoice/index');
    }

    /**
     * Returns the lowest invoice number of the current fiscal year.
     *
     * @return int
     */
    public function getLowestInvoiceNumber()
    {
        $low = R::getCell(" SELECT min(name) FROM invoice WHERE fy = ? AND MONTH(dateofslaughter) = MONTH(CURRENT_DATE())", array(
            Flight::setting()->fiscalyear
        ));
        if (! $low) {
            $low = R::getCell(" SELECT name FROM invoice WHERE fy = ? ORDER BY dateofslaughter DESC LIMIT 1", array(
                Flight::setting()->fiscalyear
            ));
        }
        return $low;
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
     * Cancelation of this invoice.
     *
     * A new invoice will be created, identically to this invoice only all monetrary
     * values will be negated. Also the current filter will reset so that the new cancelation
     * invoice will appear in the users list view.
     *
     */
    public function cancel()
    {
        Permission::check(Flight::get('user'), 'invoice', 'expunge');
        R::begin();
        try {
            $canceled = $this->record->cancelation();
            R::commit();
            Flight::get('user')->notify(I18n::__('invoice_cancel_success', null, array($this->record->name, $canceled->name)));
            unset($_SESSION['invoice']);
            $this->redirect('/invoice/index');
        } catch (Exception $e) {
            error_log($e);
            R::rollback();
            Flight::get('user')->notify(I18n::__('invoice_cancel_failure'), 'error');
            $this->redirect('/invoice/index');
        }
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
            $_SESSION['invoice'] = array(
                'fy' => $dialog['fy'],
                'lo' => $dialog['lo'],
                'hi' => $dialog['hi']
            );
            Flight::get('user')->notify(I18n::__('invoice_select_success'));
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
        $this->records = R::find('invoice', " fy = :fy AND ( name >= :lo AND name <= :hi ) ORDER BY name " . $order_dir, array(
            ':fy' => $_SESSION['invoice']['fy'],
            ':lo' => $_SESSION['invoice']['lo'],
            ':hi' => $_SESSION['invoice']['hi']
        ));
        $this->totals = R::getRow("SELECT count(id) AS count, SUM(ROUND(totalnet, 2)) AS totalnet, SUM(ROUND(totalnetitw, 2)) AS totalnetitw, SUM(ROUND(subtotalnet, 2)) AS subtotalnet, SUM(ROUND(totalnetnormal, 2)) as totalnetnormal, SUM(ROUND(totalnetfarmer, 2)) as totalnetfarmer, SUM(ROUND(totalnetother, 2) ) as totalnetother, SUM(ROUND(vatvalue, 2)) AS vatvalue, SUM(ROUND(vatvalueitw, 2)) AS vatvalueitw, SUM(ROUND(totalgros, 2)) AS totalgros, SUM(ROUND(bonusnet, 2)) AS bonusnet, SUM(ROUND(costnet, 2)) AS costnet FROM invoice WHERE fy = :fy AND ( name >= :lo AND name <= :hi )", array(
            ':fy' => $_SESSION['invoice']['fy'],
            ':lo' => $_SESSION['invoice']['lo'],
            ':hi' => $_SESSION['invoice']['hi']
        ));
        return null;
    }

    /**
     * Toggle paid attribute.
     */
    public function payment()
    {
        if ($this->record->paid) {
            $this->record->paid = 0;
        } else {
            $this->record->paid = 1;
        }
        R::store($this->record);
        Flight::render('invoice/single', array(
            'record' => $this->record
        ));
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $this->getCollection('ASC');
        $this->record = reset($this->records);
        $fy = $_SESSION['invoice']['fy'];
        $lo = $_SESSION['invoice']['lo'];
        $hi = $_SESSION['invoice']['hi'];
        $filename = I18n::__('invoice_filename', null, array(
            $fy,
            $lo,
            $hi
        ));
        $title = I18n::__('invoice_docname', null, array(
            $fy,
            $lo,
            $hi
        ));
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('pdf/invoice', array(
            'company_name' => $this->record->company->legalname,
            'language' => Flight::get('language'),
            'pdf_headline' => I18n::__('invoice_text_header', null, [$fy, $lo, $hi]),
            'record' => $this->record,
            'records' => $this->records,
            'totals' => $this->totals
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
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
        Flight::render('invoice/toolbar', array(
        ), 'toolbar');
        Flight::render('shared/header', array(), 'header');
        Flight::render('shared/footer', array(), 'footer');
        Flight::render('invoice/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("invoice_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
