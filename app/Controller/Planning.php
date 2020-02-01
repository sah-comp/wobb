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
 * Planning controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Planning extends Controller
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
     * Constructs a new Planning controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('plan', $id);
		$this->fiscalyear = Flight::setting()->fiscalyear;
    }

    /**
     * Lists all slaughterdays of the current year.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'planning', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('plan', ' YEAR(pubdate) = :fy ORDER BY pubdate DESC', array(
        	':fy' => $this->fiscalyear
        ));
        $this->render();
    }
    
    /**
     * Deletes the csb bean and all related data like deliverer beans, stock beans and so on
     * if the csb is not yet calculated. Otherwise it will show an error.
     */
    public function drop()
    {
        Permission::check(Flight::get('user'), 'planning', 'expunge');
        R::begin();
        try {
            R::trash($this->record);
            R::commit();
            Flight::get('user')->notify(I18n::__('planning_day_drop_success'));
            $this->redirect('/planning/index');
        } catch (Exception $e) {    
            error_log($e);
            //R::rollback();
            Flight::get('user')->notify(I18n::__('planning_day_drop_error'), 'error');
            $this->redirect('/planning/index');
        }
    }
	
    /**
     * Edit an adjustment bean.
     */
    public function edit()
    {
        Permission::check(Flight::get('user'), 'planning', 'edit');
        $this->layout = 'edit';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
				$this->record->calculation();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('planning_day_edit_success'));
                $this->redirect(sprintf('/planning/edit/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('planning_day_edit_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * A new slaughter charge.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'planning', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
				$this->record->calculation();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('planning_day_add_success'));
                $this->redirect(sprintf('/planning/edit/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('planning_day_add_error'), 'error');
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
		$pubdate = $this->record->localizedDate('pubdate');
        $filename = I18n::__('planning_filename', null, [$pubdate]);
        $title = I18n::__('planning_docname', null, [$pubdate]);
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('planning/print', [
            'record' => $this->record,
			'pubdate' => $pubdate
		]);
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
        Flight::render('planning/toolbar', array(
			'record' => $this->record
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('planning/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'fiscalyear' => $this->fiscalyear
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("planning_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
