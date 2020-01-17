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
                'name' => 'kind',
                'sort' => array(
                    'name' => 'kind'
                ),
                'callback' => array(
                    'name' => 'kindName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
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
                'name' => 'supplier',
                'sort' => array(
                    'name' => 'supplier'
                ),
                'filter' => array(
                    'tag' => 'text'
                ),
				'width' => '5rem'
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
                'name' => 'condition',
                'sort' => array(
                    'name' => 'condition'
                ),
                'callback' => array(
                    'name' => 'conditionName'
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
                ),
				'width' => '8rem'
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
                ),
				'width' => '8rem'
            )
        );
    }
    
    /**
     * Returns the i18n of condition token.
     *
     * @return string
     */
    public function conditionName()
    {
        return I18n::__('var_condition_' . $this->bean->condition);
    }
    
    /**
     * Returns the i18n of kind token.
     *
     * @return string
     */
    public function kindName()
    {
        return I18n::__('var_kind_' . $this->bean->kind);
    }
    
    /**
     * Returns an array with condition names.
     *
     * @return array
     */
    public function getConditions()
    {
        return array(
            'fixed',
            'disagio',
            'agio',
            'skip'
        );
    }
    
    /**
     * Returns an array with kind names.
     *
     * A var bean may be of a certain kind. Some may apply to stock damage1 or damage2 or
     * other may apply to stock quality.
     *
     * @return array
     */
    public function getKinds()
    {
        return array(
            'quality',
            'damage1',
            'damage2',
            'qs',
            'other'
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
