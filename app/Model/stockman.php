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
 * stockman model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Stockman extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->earmark = '';
        $this->bean->vvvo = '';
        $this->bean->name = '';
        $this->addConverter('relsprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('reldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('tierwohlnetperstock', array(
            new Converter_Decimal()
        ));
    }
}
