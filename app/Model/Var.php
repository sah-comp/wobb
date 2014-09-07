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
 * Var(iable) model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Var extends Model
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
            ),
            array(
                'name' => 'supplier',
                'sort' => array(
                    'name' => 'supplier'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'note',
                'sort' => array(
                    'name' => 'note'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'sprice',
                'sort' => array(
                    'name' => 'sprice'
                ),
                'callback' => array(
                    'name' => 'decimal'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
                )
            ),
            array(
                'name' => 'dprice',
                'sort' => array(
                    'name' => 'dprice'
                ),
                'callback' => array(
                    'name' => 'decimal'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
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
        $this->addConverter('sprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('dprice', array(
            new Converter_Decimal()
        ));
    }
}
