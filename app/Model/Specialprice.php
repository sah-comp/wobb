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
 * Specialprice model.
 *
 * For any supplier there may be special prices applying to stock that is damaged or stock that
 * qualifies as a Sau, schweres Schwein.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Specialprice extends Model
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
                    'name' => 'specialprice.desc'
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
        $this->bean->doesnotaffectlanuv = 0;
        $this->addConverter('sprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('dprice', array(
            new Converter_Decimal()
        ));
    }
}
