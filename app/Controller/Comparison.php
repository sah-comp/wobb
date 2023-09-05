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
 * Comparison controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Comparison extends Controller
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
     * Constructs a new Comparison controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('comparison', $id);
        $this->fiscalyear = Flight::setting()->fiscalyear;
    }

    /**
     * Lists all slaughterdays of the current year.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'comparison', 'index');
        $this->layout = 'index';
        $this->records = R::findAll('comparison', ' YEAR(startdate) = :fy ORDER BY startdate DESC', array(
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
        Permission::check(Flight::get('user'), 'comparison', 'expunge');
        R::begin();
        try {
            R::trash($this->record);
            R::commit();
            Flight::get('user')->notify(I18n::__('comparison_day_drop_success'));
            $this->redirect('/comparison/index');
        } catch (Exception $e) {
            error_log($e);
            //R::rollback();
            Flight::get('user')->notify(I18n::__('comparison_day_drop_error'), 'error');
            $this->redirect('/comparison/index');
        }
    }

    /**
     * Edit an adjustment bean.
     */
    public function edit()
    {
        Permission::check(Flight::get('user'), 'comparison', 'edit');
        $this->layout = 'edit';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                //R::debug(true);
                R::store($this->record);
                //R::debug(false);
                $this->record->compare();
                //error_log('I am back at the Controller');
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('comparison_day_edit_success'));
                $this->redirect(sprintf('/comparison/edit/%d', $this->record->getId()));
            } catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('comparison_day_edit_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * A new slaughter charge.
     */
    public function add()
    {
        Permission::check(Flight::get('user'), 'comparison', 'add');
        $this->layout = 'add';
        if (Flight::request()->method == 'POST') {
            $this->record = R::graph(Flight::request()->data->dialog, true);
            R::begin();
            try {
                R::store($this->record);
                $this->record->compare();
                R::store($this->record);
                R::commit();
                Flight::get('user')->notify(I18n::__('comparison_day_add_success'));
                $this->redirect(sprintf('/comparison/edit/%d', $this->record->getId()));
            } catch (Exception $e) {
                error_log($e);
                R::rollback();
                Flight::get('user')->notify(I18n::__('comparison_day_add_error'), 'error');
            }
        }
        $this->render();
    }

    /**
     * Generates an PDF using mPDF library and downloads it to the client.
     *
     * If there is a query parameter named 'layout', the template prices is used
     * instead of the usual template print.
     *
     * @return void
     */
    public function pdf()
    {
        $layout = 'print';
        if (Flight::request()->query['layout'] == 'prices') {
            $layout = 'print_price';
        }
        $pubdate = $this->record->localizedDate('pubdate');
        $filename = I18n::__('comparison_filename', null, [$pubdate]);
        $title = I18n::__('comparison_docname', null, [$pubdate]);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c', 'format' => 'A4']);
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('comparison/' . $layout, [
            'language' => Flight::get('language'),
            'record' => $this->record,
            'pubdate' => $pubdate
        ]);
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
        Flight::render('comparison/toolbar', array(
            'record' => $this->record
        ), 'toolbar');
        Flight::render('shared/header', array(), 'header');
        Flight::render('shared/footer', array(), 'footer');
        Flight::render('comparison/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records,
            'fiscalyear' => $this->fiscalyear
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("comparison_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
