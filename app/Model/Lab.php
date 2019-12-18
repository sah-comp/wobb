<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Model
 * @author $Author$
 * @version $Id$
 */

/**
 * Lab(oratory) model.
 *
 * Allows calculation off bulk stock within a certain date range
 * while using one base price and pricing to determine what if questions.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Lab extends Model
{
    /**
     * Calculates all stock beans according to the given pricing bean and baseprice within
     * the date range.
     *
     * @return void
     */
    public function generateReport()
    {
        $stocks = 
        R::find('stock', " (pubdate >= :startdate AND pubdate <= :enddate) AND csb_id IS NOT NULL",
            array(
                ':startdate' => $this->bean->startdate,
                ':enddate' => $this->bean->enddate
                )
        );
        //we need a fake persona
        $person = R::dispense('person');
        $person->nickname = 'XX';
        $person->pricing = $this->bean->pricing;
        
        //we need a fake sub deliverer
        $sub = R::dispense('deliverer');
        $sub->supplier = 'XX';
        $sub->earmark = 'XX0000';
        $sub->piggery = count($stocks);
        $sub->dprice = $this->bean->baseprice;
        $sub->sprice = $this->bean->baseprice;
        
        //we need to fake a deliverer. It will be deleted at the end again.
        $deliverer = R::dispense('deliverer');
        $deliverer->supplier = 'XX';
        $deliverer->earmark = 'XX0000';
        $deliverer->piggery = count($stocks);
        $deliverer->dprice = $this->bean->baseprice;
        $deliverer->sprice = $this->bean->baseprice;
        $deliverer->deliverer = $sub;
        $deliverer->person = $person;
        R::store($deliverer);
        //calculate all piggies, now. Yes, Jabba.
        foreach ($stocks as $id => $stock) {
            $stock->calculationLab($deliverer, $this->bean->pricing);
        }
        R::storeAll($stocks);
        //we clean up the mess
        R::trash($deliverer);
        R::trash($person);
        return true;
    }

    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return array(
            array(
                'name' => 'company_id',
                'sort' => array(
                    'name' => 'company.name'
                ),
                'callback' => array(
                    'name' => 'getCompanyName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'pricing_id',
                'sort' => array(
                    'name' => 'pricing.name'
                ),
                'callback' => array(
                    'name' => 'getPricingName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'baseprice',
                'sort' => array(
                    'name' => 'baseprice'
                ),
                'callback' => array(
                    'name' => 'decimal'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
                )
            ),
            array(
                'name' => 'startdate',
                'sort' => array(
                    'name' => 'startdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
            array(
                'name' => 'enddate',
                'sort' => array(
                    'name' => 'enddate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            )
        );
    }
    
    /**
     * Returns SQL string.
     *
     * @param string (optional) $fields to select
     * @param string (optional) $where
     * @param string (optional) $order
     * @param int (optional) $offset
     * @param int (optional) $limit
     * @return string $sql
     */
    public function getSql($fields = 'id', $where = '1', $order = null, $offset = null, $limit = null)
    {
		$sql = <<<SQL
		SELECT
		    {$fields}
		FROM
		    {$this->bean->getMeta('type')}
		LEFT JOIN company ON company.id = lab.company_id
		LEFT JOIN pricing ON pricing.id = lab.pricing_id
		WHERE
		    {$where}
SQL;
        //add optional order by
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        //add optional limit
        if ($offset || $limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
        return $sql;
    }
    
    /**
     * Returns the week of year from a given attribute.
     *
     * @param string Name of the attribute
     * @return int Week of the year
     */
    public function weekOfYear($attr = 'startdate')
    {
        $date = new DateTime($this->bean->$attr);
        return $date->format("W");
    }
    
    /**
     * Returns the name of this beans company.
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->bean->company->name;
    }
    
    /**
     * Returns the name of this beans pricing.
     *
     * @return string
     */
    public function getPricingName()
    {
        return $this->bean->pricing->name;
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->dirty = false;
        $this->addConverter('startdate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('enddate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('baseprice', array(
            new Converter_Decimal()
        ));
        $this->addValidator('company_id', array(
            new Validator_HasValue()
        ));
        $this->addValidator('pricing_id', array(
            new Validator_HasValue()
        ));
    }
    
    
    /**
     * update.
     *
     * This will check for file uploads.
     */
    public function update()
    {
        if ($this->bean->company_id) {
            $this->bean->company = R::load('company', $this->bean->company_id);
        } else {
            unset($this->bean->company);
        }
        if ($this->bean->pricing_id) {
            $this->bean->pricing = R::load('pricing', $this->bean->pricing_id);
        } else {
            unset($this->bean->pricing);
        }
        parent::update();
    }
}
