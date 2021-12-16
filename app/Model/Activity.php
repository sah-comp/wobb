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
 * Activity model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Activity extends Model
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
                'name' => 'stamp',
                'sort' => array(
                    'name' => 'stamp'
                ),
                'filter' => array(
                    'tag' => 'date'
                ),
                'width' => '12rem'
            ),
            array(
                'name' => 'username',
                'sort' => array(
                    'name' => 'username'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
                'width' => '10rem'
            ),
            array(
                'name' => 'message',
                'sort' => array(
                    'name' => 'message'
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
        $this->addConverter('stamp', array(
            new Converter_Mysqldatetime()
        ));
    }
}
