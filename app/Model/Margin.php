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
 * Margin model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Margin extends Model
{
    /**
     * Returns an array with attributes for lists.
     *
     * @param string (optional) $layout
     * @return array
     */
    public function getAttributes($layout = 'table')
    {
        return array(
        );
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('lo', array(
            new Converter_Decimal()
        ));
        $this->addConverter('hi', array(
            new Converter_Decimal()
        ));
        $this->addConverter('value', array(
            new Converter_Decimal()
        ));
    }
}
