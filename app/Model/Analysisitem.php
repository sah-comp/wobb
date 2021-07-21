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
        $this->bean->itwpiggery = 0;
        $this->bean->itwdamagepiggery = 0;
        $this->addConverter('piggery', array(
          new Converter_Decimal()
        ));
        $this->addConverter('itwpiggery', array(
          new Converter_Decimal()
        ));
        $this->addConverter('piggerypercentage', array(
          new Converter_Decimal()
        ));
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
        $this->addConverter('avgpricenet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricenetitw', array(
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
        $this->addConverter('damage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagepiggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('itwdamagepiggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagepiggerypercentage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotaldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotallanuvprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotalpricenetitw', array(
            new Converter_Decimal()
        ));

        $this->addConverter('damageavgmfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricenet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricelanuv', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgdprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotalpricenetitw', array(
            new Converter_Decimal()
        ));
    }
}
