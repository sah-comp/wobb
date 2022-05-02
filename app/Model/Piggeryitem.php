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
 * Piggeryitem model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Piggeryitem extends Model
{
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('stockcount', array(
          new Converter_Decimal()
        ));
        $this->addConverter('pubdate', array(
            new Converter_Mysqldate()
        ));
    }
}
