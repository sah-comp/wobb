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
 * Lanuv model.
 *
 * A lanuv model manages a certain statistic for the german authorities of
 * nature, surroundings and consumers following the FLGDV 1.
 *
 * @see http://www.gesetze-im-internet.de/flgdv_1/ 
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Lanuv extends Model
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
            array(
                'name' => 'name',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            )
        );
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
    }
}
