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
 * Piggery controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Piggery extends Controller
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
     * Constructs a new Analysis controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('piggery', $id);
        $this->fiscalyear = Flight::setting()->fiscalyear;
    }

    /**
     * A new analysis bean.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'piggery', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('piggery_add_success'));
                $this->redirect(sprintf('/piggery/piggery/%d', $this->record->getId()));
            } catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('piggery_add_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * A certain analysis summary.
     */
    public function piggery()
    {
        Permission::check(Flight::get('user'), 'piggery', 'edit');
        $this->layout = 'piggery';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                $this->record->dirty = false;
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('piggery_edit_success'));
                $this->redirect(sprintf('/piggery/piggery/%d', $this->record->getId()));
            } catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('piggery_edit_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * Generates a .csv file and downloads it to the client.
     */
    public function csv()
    {
        $filename = I18n::__('piggery_filename_csv', null, [$this->record->startdate, $this->record->enddate]);
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
             I18n::__('piggery_label_pubdate'), //Schlachtdatum
             I18n::__('piggery_label_piggery'), //Stückzahl
         ];
        $csv->heading = true;
        $csv->data = $this->getItems();
        $csv->output($filename);
        exit;
    }

    /**
     * Returns an array of all piggeryitems.
     *
     * @return array
     */
    public function getItems()
    {
        $sql = <<<SQL
         SELECT
             piggeryitem.pubdate,
             FORMAT(piggeryitem.stockcount, 0, 'de_DE') AS stockcount
         FROM
             piggeryitem
         WHERE
             piggeryitem.piggery_id = :id
         ORDER BY
             piggeryitem.pubdate ASC
 SQL;
        return R::getAll($sql, array(
             ':id' => $this->record->getId()
         ));
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * @return void
     */
    public function pdf()
    {
        $startdate = $this->record->localizedDate('startdate');
        $enddate = $this->record->localizedDate('enddate');
        $filename = I18n::__('piggery_filename', null, [$startdate]);
        $title = I18n::__('piggery_docname', null, [$startdate]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('piggery/print', [
            'language' => Flight::get('language'),
            'record' => $this->record,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * List all analysis beans.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'piggery', 'index');
        $this->layout = 'index';
        $this->records = R::find('piggery', ' company_id IS NOT NULL AND (YEAR(startdate) = :fy OR YEAR(enddate) = :fy) ORDER BY startdate DESC', array(
            ':fy' => $this->fiscalyear
        ));
        if (Flight::request()->method == 'POST') {
            Flight::get('user')->notify(I18n::__('piggery_select_success'));
        }
        $this->render();
    }

    /**
     * Renders the current layout.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('piggery/toolbar', [
            'record' => $this->record
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render('piggery/'.$this->layout, [
            'record' => $this->record,
            'records' => $this->records,
            'fiscalyear' => $this->fiscalyear
        ], 'content');
        Flight::render('html5', [
            'title' => I18n::__("piggery_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ]);
    }
}
