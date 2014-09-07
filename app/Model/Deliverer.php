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
    }
}
