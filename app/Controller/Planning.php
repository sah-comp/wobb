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
        if ( $this->record->wasCalculated()) {
            Flight::get('user')->notify(I18n::__('planning_day_drop_denied_already_billed'), 'error');
            $this->redirect('/planning/index');
        }
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
                R::commit();
                Flight::get('user')->notify(I18n::__('planning_day_edit_success'));
                $this->redirect('/planning/index');
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
                R::commit();
                Flight::get('user')->notify(I18n::__('planning_day_add_success'));
                $this->redirect(sprintf('/planning/calculation/%d', $this->record->getId()));
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
     * Calculates prices of the current slaughter charge.
     *
     * @todo this has to be a controller on its own?
     */
    public function calculation()
    {
        Permission::check(Flight::get('user'), 'planning', 'edit');
        $this->layout = 'calculation';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record); //must do this, because otherwise prices dont copy!!
                $this->record->calculation();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('planning_calculation_edit_success'));
                $this->redirect(sprintf('/planning/calculation/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('planning_calculation_edit_error'), 'error');
            }
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
        Flight::render('planning/toolbar', array(
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
