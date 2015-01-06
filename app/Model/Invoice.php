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
 * Invoice model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Invoice extends Model
{        
    /**
     * Returns the vat bean.
     *
     * @return RedBean_OODBBean
     */
    public function vat()
    {
        if ( ! $this->bean->vat ) {
            $this->bean->vat = R::dispense('vat');
        }
        return $this->bean->vat;
    }
}
