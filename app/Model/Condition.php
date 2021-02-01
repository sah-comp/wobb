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
            'stockperweight'
        );
    }

    /**
     * Returns an array with precondition names.
     *
     * @return array
     */
    public function getPreconditions()
    {
        return array(
            'none',
            'weight',
            'mfa',
            //'quality'
        );
    }

    /**
     * Returns an array with comparisons for preconditions.
     *
     * @return array
     */
    public function getComparisons()
    {
        return array(
            'none',//none
            'gt',// greater than
            'lt',// less than
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
        $this->addConverter('cvalue', array(
            new Converter_Decimal()
        ));
    }
}
