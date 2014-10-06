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
 * Condition model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Condition extends Model
{
    /**
     * Returns an array with label names.
     *
     * @return array
     */
    public function getLabels()
    {
        return array(
            'stockperitem',
            'stockperweight',
            'total'
        );
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
    }
}
