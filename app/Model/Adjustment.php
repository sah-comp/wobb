<?php
/**
 * Cinnebar.
 *
 * My lightweight no-framework framework written in PHP.
 *
 * @package Cinnebar
 * @author $Author$
 * @version $Id$
 */

/**
 * Adjustment model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Adjustment extends Model
{
    /**
     * Returns wether the adjustment was already calculated or not.
     *
     * @return bool
     */
    public function wasCalculated()
    {
        if ( $this->bean->calcdate === NULL || $this->bean->calcdate == '0000-00-00 00:00:00' ) return FALSE;
        return TRUE;
    }

    /**
     * Returns wether the adjustment was already billed or not.
     *
     * @return bool
     */
    public function wasBilled()
    {
      if ( $this->bean->billingdate === NULL || $this->bean->billingdate == '0000-00-00 00:00:00' ) return FALSE;
      return TRUE;
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
                'name' => 'pubdate',
                'sort' => array(
                    'name' => 'adjustment.pubdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
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
		LEFT JOIN company ON company.id = adjustment.company_id
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
     * Returns the name of this beans company.
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->bean->company->name;
    }

    /**
     * dispense a new adjustment bean.
     */
    public function dispense()
    {
        $this->bean->billingdate = NULL;//'0000-00-00 00:00:00';
        $this->bean->calcdate = NULL;//'0000-00-00 00:00:00';
        $this->bean->pubdate = date('Y-m-d');
        $this->addConverter('billingdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('calcdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('pubdate',
            new Converter_Mysqldate()
        );
        $this->addValidator('pubdate', array(
            new Validator_HasValue()
        ));
        $this->addValidator('company_id', array(
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
        parent::update();
    }

    /**
     * Calculates each adjustmentitem bean of this adjustment bean.
     *
     * @return void
     */
    public function calculation()
    {
        foreach ($this->bean
                      ->ownAdjustmentitem as $_id => $adjustmentitem) {
            $adjustmentitem->calculation($this->bean);
        }
        $this->bean->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated the adjustment bean
        return null;
    }

    /**
     * Generates bills for all adjustmentitem beans of this adjustment bean.
     *
     * @return void
     */
    public function billing()
    {
        $this->bean->net = 0;
        $this->bean->vatvalue = 0;
        $this->bean->gros = 0;
        foreach ($this->bean
                      ->ownAdjustmentitem as $id => $adjustmentitem) {
            $ret = $adjustmentitem->billing($this->bean);
            $this->bean->net += $ret['net'];
            $this->bean->vatvalue += $ret['vatvalue'];
            $this->bean->gros += $ret['gros'];
        }
        $this->bean->billingdate = date('Y-m-d H:i:s'); //stamp that we have billed the adjustment bean
        return null;
    }
}
