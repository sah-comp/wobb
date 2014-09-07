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
 * Purchase controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Purchase extends Controller
{
    /**
      * Container for javascripts to load.
      *
      * @var array
      */
    public $javascripts = array(
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
        $this->record = R::load('csb', $id);
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'purchase', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('csb', ' ORDER BY pubdate DESC');
        if (Flight::request()->method == 'POST') {
            //try to create a new csb bean with data from form
            //which imports the csb data to stock
            //from given stock
            //create each deliverer with its subdeliverers
            //spread data like prices and such
            //if all went well goto /purchase/day/n with given new bean
            //or generate a error notification and stay here
            Flight::get('user')->notify(I18n::__('purchase_day_select_successful'));
        }
        $this->render();
    }
    
    /**
     * A new slaughter charge.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'purchase', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->importFromCsb();
                R::store($this->record);
                $this->record->makeDeliverer();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('purchase_day_add_success'));
                $this->redirect(sprintf('/purchase/day/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_day_add_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * A current slaughter charge.
     */
    public function day()
    {
        Permission::check(Flight::get('user'), 'purchase', 'edit');
        $this->layout = 'day';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('purchase_day_edit_success'));
                $this->redirect(sprintf('/purchase/day/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('purchase_day_edit_error'), 'error');
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
        Flight::render('purchase/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('purchase/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("purchase_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default'),
            'javascripts' => $this->javascripts
        ));
    }
}
