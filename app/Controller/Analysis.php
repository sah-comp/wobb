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
 * Analysis controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Analysis extends Controller
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
        $this->record = R::load('analysis', $id);
		$this->fiscalyear = Flight::setting()->fiscalyear;
    }

    /**
     * A new analysis bean.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'analysis', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('analysis_analysis_add_success'));
                $this->redirect(sprintf('/analysis/analysis/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('analysis_analysis_add_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * A certain analysis summary.
     */
    public function analysis()
    {
        Permission::check(Flight::get('user'), 'analysis', 'edit');
        $this->layout = 'analysis';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                $this->record->dirty = false;
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('analysis_analysis_edit_success'));
                $this->redirect(sprintf('/analysis/analysis/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('analysis_analysis_edit_error'), 'error');
            }
        }
        $this->render();
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
        $filename = I18n::__('analysis_filename', null, array($startdate));
        $title = I18n::__('analysis_docname', null, array($startdate));
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('analysis/print', array(
            'record' => $this->record,
            'startdate' => $startdate,
            'enddate' => $enddate
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        $mpdf->Output($filename, 'D');
        exit;
    }

    /**
     * List all analysis beans.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'analysis', 'index');
        $this->layout = 'index';
        $this->records = R::find('analysis', ' company_id IS NOT NULL AND (YEAR(startdate) = :fy OR YEAR(enddate) = :fy) ORDER BY id DESC', array(
			':fy' => $this->fiscalyear
		));
        if (Flight::request()->method == 'POST') {
            //try to create a new csb bean with data from form
            //which imports the csb data to stock
            //from given stock
            //create each deliverer with its subdeliverers
            //spread data like prices and such
            //if all went well goto /purchase/day/n with given new bean
            //or generate a error notification and stay here
            Flight::get('user')->notify(I18n::__('analysis_select_success'));
        }
        $this->render();
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
        Flight::render('analysis/toolbar', array(
            'record' => $this->record
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('analysis/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
			'fiscalyear' => $this->fiscalyear
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("analysis_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
