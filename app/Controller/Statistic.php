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
 * Statistic controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Statistic extends Controller
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
        $this->record = R::load('lanuv', $id);
    }

    /**
     * A new lanuv statistic.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'statistic', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_add_success'));
                $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_add_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * A certain lanuv summary.
     */
    public function lanuv()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
        $this->layout = 'lanuv';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->generateReport();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_edit_success'));
                $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('statistic_lanuv_edit_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * Export stock of the lanuv bean as csv for usage with Excel.
     */
    public function csv()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
        $this->record->exportAsCsv();
        exit;
    }
    
    /**
     * Creates a CSV file as required for LANUV and tries to send it to
     * the company lanuv email address.
     */
    public function send()
    {
        Permission::check(Flight::get('user'), 'statistic', 'edit');
        try {
            $this->record->mail();
            Flight::get('user')->notify(I18n::__('statistic_lanuv_send_success'));
        } catch (Exception $e) {            
            error_log($e);
            Flight::get('user')->notify(I18n::__('statistic_lanuv_send_error'), 'error');
        }
        $this->redirect(sprintf('/statistic/lanuv/%d', $this->record->getId()));
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'statistic', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('lanuv', ' ORDER BY id DESC');
        if (Flight::request()->method == 'POST') {
            //try to create a new csb bean with data from form
            //which imports the csb data to stock
            //from given stock
            //create each deliverer with its subdeliverers
            //spread data like prices and such
            //if all went well goto /purchase/day/n with given new bean
            //or generate a error notification and stay here
            Flight::get('user')->notify(I18n::__('lanuv_select_success'));
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
        Flight::render('statistic/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('statistic/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("statistic_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
