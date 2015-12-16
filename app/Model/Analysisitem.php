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
 * Analysisitem model.
 *
 * Holds a line for each quality or checksum of a analysis bean.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Analysisitem extends Model
{   
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('sumweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgmfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgdprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotaldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotallanuvprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricelanuv', array(
            new Converter_Decimal()
        ));
    }
}
