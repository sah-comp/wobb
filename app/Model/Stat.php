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
 * Stat(istic) model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Stat extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->stamp = time();
        $this->addConverter('relsprice', new Converter_Decimal());
        $this->addConverter('reldprice', new Converter_Decimal());
        $this->addConverter('fixsprice', new Converter_Decimal());
        $this->addConverter('fixdprice', new Converter_Decimal());
    }

    /**
     * Update.
     */
    public function update()
    {
        parent::update();
    }
}
