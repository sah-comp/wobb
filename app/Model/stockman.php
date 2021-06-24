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
        $this->addConverter('reldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('tierwohlnetperstock', array(
            new Converter_Decimal()
        ));
    }
}
