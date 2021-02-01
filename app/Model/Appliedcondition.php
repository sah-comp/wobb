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
 * Appliedcondition model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Appliedcondition extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('value', array(
            new Converter_Decimal()
        ));
        $this->addConverter('factor', array(
            new Converter_Decimal()
        ));
        $this->addConverter('net', array(
            new Converter_Decimal()
        ));
    }
}
