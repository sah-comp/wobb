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
     * @return array $sum
     */
    public function calculation(RedBean_OODBBean $deliverer, RedBean_OODBBean $pricing)
    {
        $this->bean->agio = 0;
        $this->bean->disagio = 0;
        if ( ! $this->calculateFixedPrice($deliverer)) {
            $this->calculatePrice($deliverer, $pricing);
            $this->calculateDamage1Price($deliverer);
            $this->calculateDamage2Price($deliverer);
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
        
        $this->bean->totalsprice = ( $this->bean->sprice * $this->bean->weight );
        $this->bean->totaldprice = ( $this->bean->dprice * $this->bean->weight );
        $this->bean->totallanuvprice = $this->bean->totaldprice + $deliverer->calculate($this->bean);
        return null;
    }
    
    /**
     * Checks for fixed price.
     *
     * If stock has a quality with a fixed price, that one is used. Fixed prices are var beans.
     *
     * @param RedBean_OODBBean $deliverer
     * @return bool wether a fixed price was used or not
     */
    public function calculateFixedPrice(RedBean_OODBBean $deliverer)
    {
        if ( ! $fixedPrice = R::findOne('specialprice', " ( name = :quality AND deliverer_id = :del_id ) AND kind = 'quality' LIMIT 1 ", array(
            ':quality' => $this->bean->quality,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }
        
        $this->bean->agio = 0;
        $this->bean->disagio = 0;
        $this->bean->bonus = 0;
        $this->bean->sprice = $fixedPrice->sprice;
        $this->bean->dprice = $fixedPrice->dprice;
        
        $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
        $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        $this->bean->totallanuvprice = $this->bean->totaldprice;
        return true;
    }
    
    /**
     * Checks for damage1 code.
     *
     * If stock has a code in damage1 a fixed price or agio or disagio apply.
     *
     * @param RedBean_OODBBean $deliverer
     * @return bool wether a fixed price was used or not
     */
    public function calculateDamage1Price(RedBean_OODBBean $deliverer)
    {
        if ( empty($this->bean->damage1) ) return false;
        
        if ( ! $fixedPrice = R::findOne('specialprice', " ( name = :damage1 AND deliverer_id = :del_id ) AND kind = 'damage1' LIMIT 1 ", array(
            ':damage1' => $this->bean->damage1,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }
        
        if ( $fixedPrice->condition == 'fixed' ) {
            $this->bean->agio = 0;
            $this->bean->disagio = 0;
            $this->bean->bonus = 0;
            $this->bean->sprice = $fixedPrice->sprice;
            $this->bean->dprice = $fixedPrice->dprice;
            $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
            $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        } elseif ( $fixedPrice->condition == 'disagio') {
            $this->bean->totalsprice -= $fixedPrice->sprice;
            $this->bean->totaldprice -= $fixedPrice->dprice;
        } elseif ( $fixedPrice->condition == 'agio') {
            $this->bean->totalsprice += $fixedPrice->sprice;
            $this->bean->totaldprice += $fixedPrice->dprice;
        }
        
        if ( ! $fixedPrice->doesnotaffectlanuv ) {
            $this->bean->totallanuvprice = $this->bean->totaldprice;
        }
        
        return true;
    }
    
    /**
     * Checks for damage2 code.
     *
     * If stock has a code in damage2 a fixed price or agio or disagio apply.
     *
     * @param RedBean_OODBBean $deliverer
     * @return bool wether a fixed price was used or not
     */
    public function calculateDamage2Price(RedBean_OODBBean $deliverer)
    {
        if ( empty($this->bean->damage2) ) return false;
        
        if ( ! $fixedPrice = R::findOne('specialprice', " ( name = :damage2 AND deliverer_id = :del_id ) AND kind = 'damage2' LIMIT 1 ", array(
            ':damage2' => $this->bean->damage2,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }
        
        if ( $fixedPrice->condition == 'fixed' ) {
            $this->bean->agio = 0;
            $this->bean->disagio = 0;
            $this->bean->bonus = 0;
            $this->bean->sprice = $fixedPrice->sprice;
            $this->bean->dprice = $fixedPrice->dprice;
            $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
            $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        } elseif ( $fixedPrice->condition == 'disagio') {
            $this->bean->totalsprice -= $fixedPrice->sprice;
            $this->bean->totaldprice -= $fixedPrice->dprice;
        } elseif ( $fixedPrice->condition == 'agio') {
            $this->bean->totalsprice += $fixedPrice->sprice;
            $this->bean->totaldprice += $fixedPrice->dprice;
        }
        
        if ( ! $fixedPrice->doesnotaffectlanuv ) {
            $this->bean->totallanuvprice = $this->bean->totaldprice;
        }
        
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
        $this->addConverter('bonus', array(
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
        parent::update();
    }
}
