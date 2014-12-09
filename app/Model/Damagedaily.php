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
 * Damagedaily model.
 *
 * For each csb bean there is a list of special prices for damaged stock.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Damagedaily extends Model
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
                'name' => 'desc',
                'sort' => array(
                    'name' => 'damagedaily.desc'
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
     * Returns the person bean which is the supplier
     *
     * @return Model_Person
     */
    public function getSupplier()
    {
        if ( ! $person = R::findOne('person', ' nickname = ? ', array($this->bean->supplier)) ) {
            $person = R::dispense('person');
        }
        return $person;
    }
    
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
