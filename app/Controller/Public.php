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
 * Public controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Public extends Controller
{

    /**
     * Holds the id of bean.
     */
    public $id = null;

    /**
      * Container for javascripts to load.
      *
      * @var array
      */
    public $javascripts = [];

    /**
     * Holds the layout to render.
     *
     * @var string
     */
    public $layout = 'index';

    /**
     * Container for the current csb bean.
     *
     * @var Model
     */
    public $record;

    /**
     * Container for the current collection of beans.
     *
     * @var array
     */
    public $records = [];

    /**
     * Constructs a new controller.
     *
     * @param int (optional) id of a bean
     */
    public function __construct($id = null)
    {
        session_start();
        //Auth::check();
        $this->id = $id;
    }

    /**
     * Render a page.
     */
    public function deliverer()
    {
        //Permission::check(Flight::get('user'), 'deliverer', 'index');
        $this->record = R::load('deliverer', $this->id);
        $sql = "SELECT p.nickname AS nickname, p.account AS account, p.vvvo AS vvvo, REPLACE(p.name, '\r\n', ' ') AS name, p.email AS email, CONCAT(adr.street, ', ', adr.zip, ' ', adr.city) AS postaladdress, pricing.name AS pricingname, (SELECT FORMAT(sum(margin.value), 3, 'de_DE') FROM margin WHERE margin.kind = 'mfa' AND margin.op = '+' AND margin.pricing_id = p.pricing_id ORDER BY margin.lo, margin.hi) AS marginmax, REPLACE(p.noterelprice, '\r\n', ' ') AS pnote, IF(p.nextweekprice = 1, 'Mittwochspreis', '') AS pnextweekprice, FORMAT(relsprice, 3, 'de_DE') AS relsprice, FORMAT(reldprice, 3, 'de_DE') AS reldprice, FORMAT(itwrelsprice, 3, 'de_DE') AS itwrelsprice, FORMAT(itwreldprice, 3, 'de_DE') AS itwreldprice, FORMAT(fixsprice, 3, 'de_DE') AS fixsprice, FORMAT(fixdprice, 3, 'de_DE') AS fixdprice, (SELECT FORMAT(SUM(`condition`.value), 3, 'de_DE') FROM `condition` WHERE person_id = p.id) AS conditionvalue, (SELECT FORMAT(SUM(`cost`.value), 3, 'de_DE') FROM `cost` WHERE person_id = p.id) AS costvalue FROM person AS p LEFT JOIN address AS adr ON adr.person_id = p.id AND adr.label = 'billing' LEFT JOIN pricing ON pricing.id = p.pricing_id WHERE p.enabled = 1 ORDER BY p.nickname";
        $this->records = R::getAll($sql);

        $this->layout = 'deliverer';
        Flight::render('public/'.$this->layout, array(
            'record' => $this->record,
            'records' => $this->records
        ), 'content');
        Flight::render('html5', array(
            'title' => I18n::__("deliverer_head_title"),
            'language' => Flight::get('language'),
            'stylesheets' => array('custom', 'default', 'tk'),
            'javascripts' => $this->javascripts
        ));
    }
}
