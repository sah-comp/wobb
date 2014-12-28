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
 * D(eliverer)Cost model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Dcost extends Model
{
    /**
     * Returns an array with label names.
     *
     * @return array
     */
    public function getLabels()
    {
        return $this->bean->cost->getLabels();
    }
  
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('content', array(
            new Validator_HasValue()
        ));
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
