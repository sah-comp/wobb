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
    public  $base_url = '/openitem';
    
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
        if ( ! isset($_SESSION['openitem'])) {
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
        if ( ! $person = R::findOne( 'person', " nickname = :nickname ", 
            array( ':nickname' => $_SESSION['openitem']['nickname'] ) 
        )) {
            $persons = R::findAll( 'person' );
            $person = array_shift($persons);
        }
        $this->records = R::find('invoice', " fy = :fy AND person_id = :person_id AND paid = 0 ORDER BY name " . $order_dir, array(
            ':fy' => $_SESSION['openitem']['fy'],
            ':person_id' => $person->getId()
        ));
        $this->totals = R::getRow(" SELECT count(id) AS count, SUM(ROUND(totalnet, 2)) AS totalnet, SUM(ROUND(subtotalnet, 2)) AS subtotalnet, SUM(ROUND(totalnetnormal, 2)) as totalnetnormal, SUM(ROUND(totalnetfarmer, 2)) as totalnetfarmer, SUM(ROUND(totalnetother, 2) ) as totalnetother, SUM(ROUND(vatvalue, 2)) AS vatvalue, SUM(ROUND(totalgros, 2)) AS totalgros, SUM(ROUND(bonusnet, 2)) AS bonusnet, SUM(ROUND(costnet, 2)) AS costnet FROM invoice WHERE fy = :fy AND person_id = :person_id AND paid = 0", array(
            ':fy' => $_SESSION['openitem']['fy'],
            ':person_id' => $person->getId()
        ));
        return null;
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
        $filename = I18n::__('openitem_filename', null, array(
            $_SESSION['openitem']['fy'],
            $_SESSION['openitem']['nickname']
        ));
        $title = I18n::__('openitem_docname', null, array(
            $_SESSION['openitem']['fy'],
            $_SESSION['openitem']['nickname']
        ));
        $mpdf = new mPDF('c', 'A4-L');
        $mpdf->SetTitle($title);
        $mpdf->SetAuthor($this->record->company->legalname);
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('openitem/print', array(
            'record' => $this->record,
            'records' => $this->records,
            'fy' => $_SESSION['openitem']['fy'],
            'nickname' => $_SESSION['openitem']['nickname'],
            'totals' => $this->totals
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
        Flight::render('openitem/toolbar', array(
        ), 'toolbar');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('openitem/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("openitem_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
