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
 * Pricing controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Pricing extends Controller
{    
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
     * Constructs a new Purchase controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        Auth::check();
        $this->record = R::load('pricing', $id);
    }

    /**
     * Generates PDF to nicely display a pricing bean in a human readable style.
     *
     * @uses generatePDF()
     * @return void
     */
    public function internal()
    {
        $this->layout = 'internal';
        $filename = I18n::__('pricing_filename', null, 
            array(
                $this->record->name
            )
        );
        $docname = I18n::__('pricing_docname', null, 
            array(
                $this->record->name
            )
        );
        $mpdf = $this->generatePDF($filename, $docname);
        $mpdf->Output($filename, 'D');
        exit;
    }
    
    /**
     * Generates an PDF using mPDF library and return the mPDF object.
     *
     * @param string $docname defaults to 'invoice'
     * @return mPDF $mpdf
     */
    private function generatePDF($docname = 'pricing')
    {
        $mpdf = new mPDF('c', 'A4');
        $mpdf->SetTitle($docname);
        $mpdf->SetAuthor('Current User');
        $mpdf->SetDisplayMode('fullpage');
        ob_start();
        Flight::render('pricing/' . $this->layout, array(
            'record' => $this->record,
            'title' => I18n::__("pricing_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk')     
        ));
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML( $html );
        return $mpdf;
    }
}
