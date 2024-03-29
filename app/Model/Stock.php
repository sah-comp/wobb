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
 * @todo refactor calculate* and calculate*Lab functions
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
                'name' => 'vvvo',
                'sort' => array(
                    'name' => 'vvvo'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
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
     * Returns a person bean.
     *
     * Based on the value of attribute supplier this will look up the person bean and return it.
     * If no person can be found a empty person bean is returned.
     *
     * @return $person
     */
    public function getPersonBySupplier()
    {
        if (! $person = R::findOne('person', " nickname = ? ", array($this->bean->supplier))) {
            $person = R::dispense('person');
            $person->name = I18n::__('person_name_unknown');
        }
        return $person;
    }

    /**
     * Returns a string with all the damages of this stock.
     *
     * @return string
     */
    public function getDamageAsText()
    {
        if (! $this->bean->damage1 && ! $this->bean->damage2) {
            return '';
        }
        return trim($this->bean->damage1 . ' ' . $this->bean->damage2);
    }

    /**
     * Returns a literal when stock has QS.
     *
     * @return string
     */
    public function getQsAsText()
    {
        if (! $this->bean->qs) {
            return '';
        }
        return 'QS';
    }

    /**
     * Calculates the price of this stock bean according to given parameters by the deliverer bean.
     *
     * @param $deliverer
     * @param $pricing
     * @param $comparison_mode (optional) defaults to false
     * @return void
     */
    public function calculation($deliverer, $pricing, $comparison_mode = false)
    {
        $old_totaldpricenet = $this->bean->old('totaldpricenet');

        $this->bean->agio = 0;
        $this->bean->disagio = 0;
        $lanuv_tax = $deliverer->calculate($this->bean);

        if ($comparison_mode) {
            if ($this->bean->damage1 != '' || $this->hadFixedPrice()) {
                // use the calculated damage price as it is fixed and may have complications, aka costs and such
            } else {
                //error_log('calculate for comparison');
                $this->calculatePrice($deliverer, $pricing, $lanuv_tax);
                $this->bean->totaldpricenet = $this->bean->totaldprice - $this->bean->cost + $this->bean->bonus;
                if (strpos($this->bean->damage2, 'L') !== false) { // any L in damage2 represents a liver damage
                    $this->bean->totaldpricenet -= 1.02;
                }
            }
            /*
            $diff = $old_totaldpricenet - $this->bean->totaldpricenet;
            if ($diff != 0) {
                error_log($diff . ' = ' . $old_totaldpricenet . ' - ' . $this->bean->totaldpricenet . ' in stock price on stock id ' . $this->bean->getId());
                $this->bean->totaldpricenet = $old_totaldpricenet;
            }
            */
        } else {
            // usual calculation, not comparison mode
            if (! $this->calculateFixedPrice($deliverer, $lanuv_tax)) {
                $this->calculatePrice($deliverer, $pricing, $lanuv_tax);
            }
            $this->calculateDamage1Price($deliverer, $lanuv_tax);
            $this->calculateDamage2Price($deliverer, $lanuv_tax);

            $this->bean->totaldpricenet = $this->bean->totaldprice - $this->bean->cost + $this->bean->bonus;

            $this->bean->totaldpricenetitw = $this->bean->totaldpricenet + $this->bean->tierwohlnetperstock;
            $this->bean->totallanuvprice = $this->bean->totaldpricenetitw;
        }

        return null;
    }

    /**
     * Checks if the stock was calculated with a fixed price or not.
     * @return bool
     */
    public function hadFixedPrice():bool
    {
        if ($var = R::findOne('var', " kind = 'quality' AND name = :quality ", [':quality' => $this->bean->quality])) {
            return true;
        }
        return false;
    }

    /**
     * Calculate the stock beans prices according to deliverer bean settings.
     *
     * @param $deliverer
     * @param $pricing
     * @param float $tax will be added to the lanuv total price
     * @return void
     */
    public function calculatePrice($deliverer, $pricing, $tax)
    {
        $pricing->calculate($this->bean, $deliverer);

        $this->bean->sprice = $deliverer->sprice + $this->bean->agio - $this->bean->disagio;
        $this->bean->dprice = $deliverer->dprice + $this->bean->agio - $this->bean->disagio;

        $this->bean->totalsprice = ($this->bean->sprice * $this->bean->weight);
        $this->bean->totaldprice = ($this->bean->dprice * $this->bean->weight);

        //$this->bean->totalspriceitw = $this->bean->totalsprice + $this->bean->tierwohlnetperstock;
        //$this->bean->totaldpriceitw = $this->bean->totaldprice + $this->bean->tierwohlnetperstock;

        //$this->bean->totallanuvprice = $this->bean->totaldprice + $tax + $this->bean->tierwohlnetperstock;
        return null;
    }

    /**
     * Checks for fixed price.
     *
     * If stock has a quality with a fixed price, that one is used. Fixed prices are var beans.
     *
     * @param $deliverer
     * @param float $tax
     * @return bool wether a fixed price was used or not
     */
    public function calculateFixedPrice($deliverer, $tax)
    {
        if (! $fixedPrice = R::findOne('specialprice', " ( name = :quality AND deliverer_id = :del_id ) AND kind = 'quality' LIMIT 1 ", array(
            ':quality' => $this->bean->quality,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }

        $this->bean->agio = 0;
        $this->bean->disagio = 0;
        //$this->bean->bonus = 0;
        $this->bean->sprice = $fixedPrice->sprice;
        $this->bean->dprice = $fixedPrice->dprice;

        $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
        $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;

        $this->calculateFixedpriceCost($fixedPrice);

        //$this->bean->totallanuvprice = $this->bean->totaldprice + $tax + $this->bean->tierwohlnetperstock;
        return true;
    }

    /**
     * Checks for damage1 code.
     *
     * If stock has a code in damage1 a fixed price or agio or disagio apply.
     * If the fixedPrice condition is not either of type 'fixed', 'agio' or 'disagio'
     * nothing will happen and the stock is left with the already calculated price.
     * This applies e.g. for 'Binneneber', damage code '02'.
     *
     * @param $deliverer
     * @param float $tax
     * @return bool wether a fixed price was used or not
     */
    public function calculateDamage1Price($deliverer, $tax)
    {
        if (empty($this->bean->damage1)) {
            return false;
        }

        if (! $fixedPrice = R::findOne('specialprice', " ( name = :damage1 AND deliverer_id = :del_id ) AND kind = 'damage1' LIMIT 1 ", array(
            ':damage1' => $this->bean->damage1,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }

        if ($fixedPrice->condition == 'fixed') {
            $this->bean->agio = 0;
            $this->bean->disagio = 0;
            //$this->bean->bonus = 0;
            $this->bean->sprice = $fixedPrice->sprice;
            $this->bean->dprice = $fixedPrice->dprice;
            $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
            $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        } elseif ($fixedPrice->condition == 'disagio') {
            $this->bean->totalsprice -= $fixedPrice->sprice;
            $this->bean->totaldprice -= $fixedPrice->dprice;
        } elseif ($fixedPrice->condition == 'agio') {
            $this->bean->totalsprice += $fixedPrice->sprice;
            $this->bean->totaldprice += $fixedPrice->dprice;
        }

        $this->calculateFixedpriceCost($fixedPrice);

        if (! $fixedPrice->doesnotaffectlanuv) {
            //$this->bean->totallanuvprice = $this->bean->totaldprice + $tax + $this->bean->tierwohlnetperstock;
        }

        return true;
    }

    /**
     * Checks for damage2 code.
     *
     * If stock has a code in damage2 a fixed price or agio or disagio apply.
     *
     * @param $deliverer
     * @param float §tax
     * @return bool wether a fixed price was used or not
     */
    public function calculateDamage2Price($deliverer, $tax)
    {
        if (empty($this->bean->damage2)) {
            return false;
        }

        if (! $fixedPrice = R::findOne('specialprice', " ( name = :damage2 AND deliverer_id = :del_id ) AND kind = 'damage2' LIMIT 1 ", array(
            ':damage2' => $this->bean->damage2,
            ':del_id' => $deliverer->deliverer->getId()
        ))) {
            return false;
        }

        if ($fixedPrice->condition == 'fixed') {
            $this->bean->agio = 0;
            $this->bean->disagio = 0;
            //$this->bean->bonus = 0;
            $this->bean->sprice = $fixedPrice->sprice;
            $this->bean->dprice = $fixedPrice->dprice;
            $this->bean->totalsprice = $this->bean->sprice * $this->bean->weight;
            $this->bean->totaldprice = $this->bean->dprice * $this->bean->weight;
        } elseif ($fixedPrice->condition == 'disagio') {
            // e.g. damag2 = L and condition is "Abzug" 1,02 euro is subtracted from the total price
            $this->bean->totalsprice -= $fixedPrice->sprice;
            $this->bean->totaldprice -= $fixedPrice->dprice;
        } elseif ($fixedPrice->condition == 'agio') {
            $this->bean->totalsprice += $fixedPrice->sprice;
            $this->bean->totaldprice += $fixedPrice->dprice;
        }

        $this->calculateFixedpriceCost($fixedPrice);

        if (! $fixedPrice->doesnotaffectlanuv) {
            //$this->bean->totallanuvprice = $this->bean->totaldprice + $tax + $this->bean->tierwohlnetperstock;
        }

        return true;
    }

    /**
     * The given fixedprice eventually has additional costs to be applied.
     *
     * @param $fixedprice
     * @return void
     */
    public function calculateFixedpriceCost($fixedprice)
    {
        if (! $fixedprice->ownScost) {
            return false;
        }
        $sum = 0;
        foreach ($fixedprice->ownScost as $id => $cost) {
            if ($cost->label == 'flat') {
                $sum += $cost->value;
            } elseif ($cost->label == 'stockperitem') {
                $sum += $cost->value;
            } elseif ($cost->label == 'stockperweight') {
                $sum += $cost->value * $this->bean->weight;
            }
        }
        $this->bean->totalsprice -= $sum;
        $this->bean->totaldprice -= $sum;
    }

    /**
     * Return itw label if this stock has itw flag raised.
     *
     * @return string
     */
    public function getItwAsText()
    {
        if ($this->bean->itw) {
            return I18n::__('invoice_internal_label_itw');
        }
        return '';
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->itw = false; // initiative Tierwahl flag
        $this->bean->tierwohlnetperstock = 0;
        $this->addConverter('pubdate', array(
            new Converter_Mysqldate()
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
        // ITW
        $this->addConverter('tierwohlnetperstock', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totaldpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('bonus', array(
            new Converter_Decimal()
        ));
        $this->addConverter('bonusitem', array(
            new Converter_Decimal()
        ));
        $this->addConverter('bonusweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('cost', array(
            new Converter_Decimal()
        ));
        $this->addConverter('costitem', array(
            new Converter_Decimal()
        ));
        $this->addConverter('costweight', array(
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
