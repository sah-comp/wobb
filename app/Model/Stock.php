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
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
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
                    'tag' => 'number'
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
                    'tag' => 'number'
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
                    'tag' => 'number'
                )
            ),
            array(
                'name' => 'damage1',
                'sort' => array(
                    'name' => 'damage1'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'damage2',
                'sort' => array(
                    'name' => 'damage2'
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
     * Calculates the price of this stock bean according to given parameters by the deliverer bean.
     *
     * @param RedBean_OODBBean $deliverer
     * @param RedBean_OODBBean $pricing
     * @return void
     */
    public function calculation(RedBean_OODBBean $deliverer, RedBean_OODBBean $pricing)
    {
        $this->bean->agio = 0;
        $this->bean->disagio = 0;
        if ( ! $this->calculateFixedPrice($deliverer)) {
            $this->calculatePrice($deliverer, $pricing);
        }
        return null;
    }
    
    /**
     * Calculate the stock beans prices according to deliverer bean settings.
     *
     * @param RedBean_OODBBean $deliverer
     * @param RedBean_OODBBean $pricing
     * @return void
     */
    public function calculatePrice(RedBean_OODBBean $deliverer, RedBean_OODBBean $pricing)
    {
        
        $pricing->calculate($this->bean, $deliverer);
        
        $this->bean->sprice = $deliverer->sprice + $this->bean->agio - $this->bean->disagio;
        $this->bean->dprice = $deliverer->dprice + $this->bean->agio - $this->bean->disagio;
        
        $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
        $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        
        return null;
    }
    
    /**
     * Checks for fixed price.
     *
     * If stock has a code in damage1 that code will be looked up in var. If there is either
     * a var entry for the given deliverers supplier code and the given damage code the fixed
     * price is used and the function will return true.
     * If no fixed price can be found the function will return false.
     *
     * @param RedBean_OODBBean $deliverer
     * @return bool wether a fixed price was used or not
     */
    public function calculateFixedPrice(RedBean_OODBBean $deliverer)
    {
        if ( ! $fixedPrice = R::findOne('var', " ( name = :quality AND supplier = :supplier ) OR ( name = :quality AND supplier = '') LIMIT 1 ", array(
            ':quality' => $this->bean->quality,
            ':supplier' => $deliverer->supplier
        ))) {
            
            if ( empty( $this->bean->damage1 )) return false;
            if ( ! $fixedPrice = R::findOne('var', " ( name = :damage AND supplier = :supplier ) OR ( name = :damage AND supplier = '') LIMIT 1 ", array(
                ':damage' => $this->bean->damage1,
                ':supplier' => $deliverer->supplier
            ))) {
                return false;
            }
        }
        $this->bean->sprice = $fixedPrice->sprice;
        $this->bean->dprice = $fixedPrice->dprice;
        return true;
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
        $this->addConverter('mfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('flesh', array(
            new Converter_Decimal()
        ));
        $this->addConverter('speck', array(
            new Converter_Decimal()
        ));
        $this->addConverter('tare', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('dprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalsprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totaldprice', array(
            new Converter_Decimal()
        ));
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
    }
    
    /**
     * Update.
     */
    public function update()
    {
        $this->bean->totalsprice = $this->bean->weight * $this->bean->sprice;
        $this->bean->totaldprice = $this->bean->weight * $this->bean->dprice;
        parent::update();
    }
}
