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
 * Adjustment controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Adjustment extends Controller
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
     * Container for the current adjustment bean.
     *
     * @var Model_Csb
     */
    public $record;
    
    /**
     * Container for the current collection of adjustment beans.
     *
     * @var array
     */
    public $records;
	
	/**
	 * Holds the current fiscal year.
	 *
	 * @var int
	 */
	public $fiscalyear;
    
    /**
     * Constructs a new Adjustment controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('adjustment', $id);
		$this->fiscalyear = Flight::setting()->fiscalyear;
    }

    /**
     * Choose an already existing day or create a new one.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'adjustment', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('adjustment', ' YEAR(pubdate) = :fy ORDER BY pubdate DESC', array(
        	':fy' => $this->fiscalyear
        ));
        $this->render();
    }
    
    /**
     * A new adjustment series.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'adjustment', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->calculation();
                R::store($this->record);
                $this->record->billing();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('adjustment_day_add_success'));
                $this->redirect('/adjustment/index');
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('adjustment_day_add_error'), 'error');
            }
        }
        $this->render();
    }
    
    /**
     * Edit an adjustment bean.
     */
    public function edit()
    {
        Permission::check(Flight::get('user'), 'adjustment', 'edit');
        $this->layout = 'edit';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->calculation();
                R::store($this->record);
                $this->record->billing();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('adjustment_day_edit_success'));
                //$this->redirect(sprintf('/adjustment/edit/%d', $this->record->getId()));
                $this->redirect('/adjustment/index');
            }
            catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('adjustment_day_edit_error'), 'error');
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
        $filename = I18n::__('adjustment_filename', null, array($pubdate));
        $title = I18n::__('adjustment_docname', null, array($pubdate));
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('adjustment/print', array(
            'record' => $this->record,
            'pubdate' => $pubdate
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
        Flight::render('adjustment/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('adjustment/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
			'fiscalyear' => $this->fiscalyear
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("adjustment_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
