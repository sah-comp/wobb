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
 * Cost model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Cost extends Model
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
            'flat'
        );
    }
  
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->content = '';
        $this->addValidator('content', array(
            new Validator_HasValue()
        ));
        $this->addConverter('value', array(
            new Converter_Decimal()
        ));
    }
}
