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
 * Stock model.
 *
 * A stock is any slaughtered animal body, foremost a pig.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Stock extends Model
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
                'name' => 'buyer',
                'sort' => array(
                    'name' => 'buyer'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'pubdate',
                'sort' => array(
                    'name' => 'pubdate'
                ),
                'callback' => array(
                    'name' => 'localizedDateTime'
                ),
                'filter' => array(
                    'tag' => 'datetime'
                )
            ),
            array(
                'name' => 'earmark',
                'sort' => array(
                    'name' => 'earmark'
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
                'class' => 'number',
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'quality',
                'sort' => array(
                    'name' => 'quality'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'weight',
                'sort' => array(
                    'name' => 'weight'
                ),
                'callback' => array(
                    'name' => 'decimal'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'mfa',
                'sort' => array(
                    'name' => 'mfa'
                ),
                'callback' => array(
                    'name' => 'decimal'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'damage1',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'damage2',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'qs',
                'sort' => array(
                    'name' => 'qs'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                )
            )
        );
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('pubdate', array(
            new Converter_MysqlDate()
        ));
        $this->addConverter('weight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('weight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('mfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('flesh', array(
            new Converter_Decimal()
        ));
        $this->addConverter('speck', array(
            new Converter_Decimal()
        ));
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
    }
}
