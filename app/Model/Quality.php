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
 * Quality model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Quality extends Model
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
                'name' => 'sequence',
                'sort' => array(
                    'name' => 'sequence'
                ),
                'filter' => array(
                    'tag' => 'number'
                ),
				'width' => '5rem'
            ),
            array(
                'name' => 'name',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
				'width' => '5rem'
            ),
            array(
                'name' => 'desc',
                'sort' => array(
                    'name' => 'quality.desc'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'enabled',
                'sort' => array(
                    'name' => 'enabled'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                ),
				'width' => '8rem'
            ),
        );
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->name = '';
        $this->bean->desc = '';
        $this->addValidator('name', array(
            new Validator_HasValue(),
            new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'name'))
        ));
    }
}
