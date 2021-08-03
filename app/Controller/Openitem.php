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
 * Openitem controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Openitem extends Controller
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
    public $base_url = '/openitem';

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
     * Container for person beans.
     *
     * @var array
     */
    public $persons = array();

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
        if (! isset($_SESSION['openitem'])) {
            $_SESSION['openitem'] = array(
                'fy' => Flight::setting()->fiscalyear,
                'nickname' => ''

            );
        }
        $this->record = R::load('invoice', $id);
    }

    /**
     * Clear the filter and start over.
     */
    public function clearfilter()
    {
        Permission::check(Flight::get('user'), 'openitem', 'index');
        unset($_SESSION['openitem']);
        $this->redirect('/openitem/index');
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'openitem', 'index');
        $this->layout = 'index';
        if (Flight::request()->method == 'POST') {
            $dialog = Flight::request()->data->dialog;
            $_SESSION['openitem'] = array(
                'fy' => $dialog['fy'],
                'nickname' => $dialog['nickname']
            );
            Flight::get('user')->notify(I18n::__('openitem_select_success'));
            $this->redirect($this->base_url);
        }
        $this->persons = R::findAll('person', " ORDER BY name ");
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
        if ($_SESSION['openitem']['nickname'] == '') {
            $this->records = R::find('invoice', " fy = :fy AND paid = 0 ORDER BY name " . $order_dir, array(
                ':fy' => $_SESSION['openitem']['fy']
            ));
            $this->totals = R::getRow(" SELECT count(id) AS count, SUM(ROUND(totalnet, 2)) AS totalnet, SUM(ROUND(totalnetitw, 2)) AS totalnetitw, SUM(ROUND(subtotalnet, 2)) AS subtotalnet, SUM(ROUND(totalnetnormal, 2)) as totalnetnormal, SUM(ROUND(totalnetfarmer, 2)) as totalnetfarmer, SUM(ROUND(totalnetother, 2) ) as totalnetother, SUM(ROUND(vatvalue, 2)) AS vatvalue, SUM(ROUND(vatvalueitw, 2)) AS vatvalueitw, SUM(ROUND(totalgros, 2)) AS totalgros, SUM(ROUND(bonusnet, 2)) AS bonusnet, SUM(ROUND(costnet, 2)) AS costnet FROM invoice WHERE fy = :fy AND paid = 0", array(
                ':fy' => $_SESSION['openitem']['fy']
            ));
        } else {
            if (! $person = R::findOne(
                'person',
                " nickname = :nickname ",
                array( ':nickname' => $_SESSION['openitem']['nickname'] )
            )) {
                $persons = R::findAll('person');
                $person = array_shift($persons);
            }
            $this->records = R::find('invoice', " fy = :fy AND person_id = :person_id AND paid = 0 ORDER BY name " . $order_dir, array(
                ':fy' => $_SESSION['openitem']['fy'],
                ':person_id' => $person->getId()
            ));
            $this->totals = R::getRow(" SELECT count(id) AS count, SUM(ROUND(totalnet, 2)) AS totalnet, SUM(ROUND(totalnetitw, 2)) AS totalnetitw, SUM(ROUND(subtotalnet, 2)) AS subtotalnet, SUM(ROUND(totalnetnormal, 2)) as totalnetnormal, SUM(ROUND(totalnetfarmer, 2)) as totalnetfarmer, SUM(ROUND(totalnetother, 2) ) as totalnetother, SUM(ROUND(vatvalue, 2)) AS vatvalue, SUM(ROUND(vatvalueitw, 2)) AS vatvalueitw, SUM(ROUND(totalgros, 2)) AS totalgros, SUM(ROUND(bonusnet, 2)) AS bonusnet, SUM(ROUND(costnet, 2)) AS costnet FROM invoice WHERE fy = :fy AND person_id = :person_id AND paid = 0", array(
                ':fy' => $_SESSION['openitem']['fy'],
                ':person_id' => $person->getId()
            ));
        }
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
        Flight::render('openitem/single', array(
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
        $fy = $_SESSION['openitem']['fy'];
        $nickname = $_SESSION['openitem']['nickname'];
        $filename = I18n::__('openitem_filename', null, array(
            $fy,
            $nickname
        ));
        $title = I18n::__('openitem_docname', null, array(
            $fy,
            $nickname
        ));
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4-L']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('pdf/invoice', array(
            'company_name' => $this->record->company->legalname,
            'language' => Flight::get('language'),
            'pdf_headline' => I18n::__('openitem_text_header', null, [$fy, $nickname]),
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
     * Export the Open Item list as .csv file
     *
     * @return void
     */
    public function csv()
    {
        $filename = I18n::__('openitem_filename_csv');
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
            I18n::__('openitem_invoice_name'), //Gutschrift
            I18n::__('openitem_invoice_dateofslaughter'), //Schlachtdatum
            I18n::__('openitem_invoice_deliverer_account'), //Konto Lieferant
            I18n::__('openitem_invoice_deliverer_name'), //NameLieferant
            I18n::__('openitem_invoice_totalnet'), //Warenwert
            I18n::__('openitem_invoice_bonusnet'), //Bonus
            I18n::__('openitem_invoice_costnet'), //Kosten
            I18n::__('openitem_invoice_subtotalnet'), //Netto
            I18n::__('openitem_invoice_vat'), //UST in Prozent
            I18n::__('openitem_invoice_vatvalue'), //Steuerwert
            I18n::__('openitem_invoice_totalnetitw'), //ITW Netto
            I18n::__('openitem_invoice_vatvalueitw'), //ITW UST 19 Wert
            I18n::__('openitem_invoice_totalgros') //Brutto
        ];
        $csv->heading = true;
        $csv->data = $this->getInvoices();
        $csv->output($filename);
    }

    /**
     * Returns an array of all stock within the lanuv time periode.
     *
     * @return array
     */
    public function getInvoices()
    {
        $sql = <<<SQL
        SELECT
            invoice.name,
            invoice.dateofslaughter AS dateofslaughter,
            person.account,
            REPLACE(person.name, "\r\n", " "),
            FORMAT(invoice.totalnet, 2, 'de_DE') AS totalnet,
            FORMAT(invoice.bonusnet, 2, 'de_DE') AS bonusnet,
            FORMAT(invoice.costnet, 2, 'de_DE') AS costnet,
            FORMAT(invoice.subtotalnet, 2, 'de_DE') AS subtotalnet,
            FORMAT(vat.value, 2, 'de_DE') AS vat,
            FORMAT(invoice.vatvalue, 2, 'de_DE') AS vatvalue,
            FORMAT(invoice.totalnetitw, 2, 'de_DE') AS totalnetitw,
            FORMAT(invoice.vatvalueitw, 2, 'de_DE') AS vatvalueitw,
            FORMAT(invoice.totalgros, 2, 'de_DE') AS totalgros
        FROM
            invoice
        LEFT JOIN
            person ON person.id = invoice.person_id
        LEFT JOIN
            vat ON vat.id = invoice.vat_id
        WHERE
            invoice.paid = 0 AND
            invoice.fy = :fy
        ORDER BY
            invoice.name ASC
SQL;
        return R::getAll($sql, array(
            ':fy' => $_SESSION['openitem']['fy']
        ));
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
        Flight::render('openitem/toolbar', array(
        ), 'toolbar');
        Flight::render('shared/header', array(), 'header');
        Flight::render('shared/footer', array(), 'footer');
        Flight::render('openitem/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'persons' => $this->persons
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("openitem_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
