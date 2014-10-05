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
 * Deliverer model.
 *
 * A deliverer is a group of deliverers or a single deliverer who delivered stock
 * on a certain day.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Deliverer extends Model
{    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('sprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('dprice', array(
            new Converter_Decimal()
        ));
        
        $this->addConverter('calcdate', array(
            new Converter_MysqlDatetime()
        ));
    }
    
    /**
     * Returns wether the deliverer was already calculated or not.
     *
     * @return bool
     */
    public function wasCalculated()
    {
        return ( $this->bean->calcdate != '0000-00-00 00:00:00');
    }
    
    /**
     * Calculates the prices of all stock that belongs to this deliverer of the given csb bean and
     * returns an array with a summery of the calculation.
     *
     * @param RedBean_OODBBean $csb
     * @return array
     */
    public function calculation($csb)
    {
        $stocks = R::find('stock', " csb_id = ? AND earmark = ? ORDER BY weight ", array(
            $csb->getId(),
            $this->bean->earmark
        ));
        if ( ! $pricing = $this->bean->person->pricing) {
            throw new Exception(I18n::__('Missing pricing information on deliverer ' . $this->bean->supplier));
        }
        $ret = array(
            'totalnet' => 0,
            'totalweight' => 0,
            'totalmfa' => 0,
            'hasmfacount' => 0,
            'piggery' => 0
        );
        foreach ($stocks as $id => $stock) {
            $stock->calculation($this->bean, $pricing);
            $ret['totalnet'] += $stock->totaldprice;
            $ret['totalweight'] += $stock->weight;
            $ret['totalmfa'] += $stock->mfa;
            if ($stock->mfa) $ret['hasmfacount']++;
            $ret['piggery']++;
        }
        $this->bean->calcdate = date('Y-m-d H:i:s');
        R::storeAll($stocks);
        return $ret;
    }
}
