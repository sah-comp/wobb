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
 * Deliverer model.
 *
 * A deliverer is a group of deliverers or a single deliverer who delivered stock
 * on a certain day.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Deliverer extends Model
{    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->enabled = true;
        $this->addConverter('sprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('dprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalnet', array(
            new Converter_Decimal()
        ));
        
        $this->addConverter('calcdate', array(
            new Converter_MysqlDatetime()
        ));
    }

    /**
     * Returns wether the deliverer needs a service calculation or billing.
     *
     * @return bool
     */
    public function hasService()
    {
        return $this->bean->person->hasservice;
    }

    /**
     * Returns information about this deliverer.
     *
     * @return string
     */
    public function getInformation()
    {
        if ( ! $this->bean->person->pricing ) return I18n::__('deliverer_person_pricemask_not_set');
        return I18n::__('deliverer_information_mask', null, array(
            '',
            $this->bean->person->pricing->name
        ));
    }
    
    /**
     * Returns wether the deliverer was already billed or not.
     *
     * A deliverer was billed if the invoice bean it owns already was stored.
     *
     * @return bool
     */
    public function wasBilled()
    {
        if ( ! $this->bean->invoice()->getId()) return false;
        return true;
    }
    
    /**
     * Returns the an invoice bean.
     *
     * @return RedBean_OODBBean
     */
    public function invoice()
    {
        if ( ! $this->bean->invoice ) {
            $this->bean->invoice = R::dispense('invoice');
        }
        return $this->bean->invoice;
    }

    /**
     * Returns wether the deliverer was already calculated or not.
     *
     * @return bool
     */
    public function wasCalculated()
    {
        return ( $this->bean->calcdate != '0000-00-00 00:00:00');
    }
    
    /**
     * Calculates the cost beans of this deliverer.
     *
     * Based on the method of the dcost bean and the number of stock or weight
     * the additional costs will be calculated.
     *
     * @return bool
     */
    public function calcCost()
    {
        $this->bean->totalcost = 0;
        foreach ($this->bean->ownDcost as $dcost_id => $dcost) {
            switch ($dcost->label) {
                case 'stockperitem':
                    $dcost->factor = $this->bean->piggery;
                    break;
                case 'stockperweight':
                    $dcost->factor = $this->bean->totalweight;
                    break;
                default:
                    $dcost->factor = 1;
                    break;
            }
            $dcost->net = $dcost->factor * $dcost->value;
            $this->bean->totalcost += $dcost->net;
        }
        $this->bean->subtotalnet = $this->bean->totalnet - $this->bean->totalcost;
        return true;
    }
    
    /**
     * Calculates the vat values of this bean.
     *
     * @return bool
     */
    public function calcVat()
    {
        $this->bean->vatvalue = $this->bean->subtotalnet * $this->bean->person->vat->value / 100;
        $this->bean->totalgros = $this->bean->subtotalnet + $this->bean->vatvalue;
        return true;
    }
    
    /**
     * Calculates conditions of this deliverer with given stock bean and returns the added bonus.
     *
     * @param RedBean_OODBBean $stock
     * @return float
     */
    public function calculate(RedBean_OODBBean $stock)
    {
        if ( ! $this->bean->person) return false;
        $conditions = $this->bean->person->ownCondition; // fetch it from the person
        if ( count ($conditions) == 0) return false;
        $bonus = 0;
        foreach ($conditions as $id => $condition) {
            switch ( $condition->label ) {
                case 'stockperitem':
                    $bonus += $condition->value;
                    break;
                
                case 'stockperweight':
                    $bonus += $stock->weight * $condition->value;
                    break;
            
                default:
                    // dunno?! nothing.
                    break;
            }
        }
        $stock->bonus = $bonus;
        return (float)$bonus;
    }
    
    /**
     * Returns an array with special prices for damaged stock.
     *
     * @todo Clean up the searches and sections damage1, damage2 and quality to make it clearer what happens
     *
     * @return array
     */
    public function getSpecialPrices()
    {
        if ( ! $this->bean->ownSpecialprice) {
            // damage1
            $stocks = R::getAll("SELECT COUNT(id) AS total, damage1 FROM stock WHERE csb_id = ? AND supplier = ? AND damage1 !='' GROUP BY damage1 ORDER BY damage1 ", array($this->bean->csb->getId(), $this->bean->supplier));
            foreach ($stocks as $id => $stock) {
                
                if ( ! $var = R::findOne('var', " (( name = :damage1 AND supplier = :supplier ) OR ( name = :damage1 AND supplier = '')) AND kind = 'damage1' LIMIT 1 ", array(
                    ':damage1' => $stock['damage1'],
                    ':supplier' => $this->bean->supplier
                ))) {
                    $var = R::dispense('var');
                }
                
                $price = R::dispense('specialprice');
                $price->piggery = $stock['total'];
                $price->doesnotaffectlanuv = $var->doesnotaffectlanuv;
                $price->kind = $var->kind;
                $price->sprice = $var->sprice;
                $price->dprice = $var->dprice;
                $price->name = $var->name;
                $price->note = $var->note;
                $price->condition = $var->condition;
                $this->bean->ownSpecialprice[] = $price;
            }
            
            // damage2
            $stocks = R::getAll("SELECT COUNT(id) AS total, damage2 FROM stock WHERE csb_id = ? AND supplier = ? AND damage2 !='' GROUP BY damage2 ORDER BY damage2 ", array($this->bean->csb->getId(), $this->bean->supplier));
            foreach ($stocks as $id => $stock) {
                
                if ( ! $var = R::findOne('var', " (( name = :damage2 AND supplier = :supplier ) OR ( name = :damage2 AND supplier = '')) AND kind = 'damage2' LIMIT 1 ", array(
                    ':damage2' => $stock['damage2'],
                    ':supplier' => $this->bean->supplier
                ))) {
                    $var = R::dispense('var');
                }
                
                $price = R::dispense('specialprice');
                $price->piggery = $stock['total'];
                $price->doesnotaffectlanuv = $var->doesnotaffectlanuv;
                $price->kind = $var->kind;
                $price->sprice = $var->sprice;
                $price->dprice = $var->dprice;
                $price->name = $var->name;
                $price->note = $var->note;
                $price->condition = $var->condition;
                $this->bean->ownSpecialprice[] = $price;
            }
            
            $qualities = R::find('var', " kind='quality' AND supplier = ''");
            foreach ($qualities as $id => $quality) {
                // quality
                $stocks = R::getAll("SELECT COUNT(id) AS total, quality FROM stock WHERE csb_id = ? AND supplier = ? AND quality = ? GROUP BY quality ORDER BY quality ", array($this->bean->csb->getId(), $this->bean->supplier, $quality->name));
                foreach ($stocks as $id => $stock) {

                    if ( ! $var = R::findOne('var', " (( name = :quality AND supplier = :supplier ) OR ( name = :quality AND supplier = '')) AND kind = 'quality' LIMIT 1 ", array(
                        ':quality' => $stock['quality'],
                        ':supplier' => $this->bean->supplier
                    ))) {
                        $var = R::dispense('var');
                    }

                    $price = R::dispense('specialprice');
                    $price->piggery = $stock['total'];
                    $price->doesnotaffectlanuv = $var->doesnotaffectlanuv;
                    $price->kind = $var->kind;
                    $price->sprice = $var->sprice;
                    $price->dprice = $var->dprice;
                    $price->name = $var->name;
                    $price->note = $var->note;
                    $price->condition = $var->condition;
                    $this->bean->ownSpecialprice[] = $price;
                }
            }
        }
        return $this->bean->ownSpecialprice;
    }

    /**
     * Generates a bill for this deliverer for the given slaughterday csb bean.
     *
     * @param RedBean_OODBBean $csb
     * @return void
     */
    public function billing($csb)
    {
        if ( ! $this->bean->invoice()->name ) {
            //$this->bean->invoice = R::dispense('invoice');
            if ( ! $nextbillingnumber = $csb->company->nextBillingnumber() ) {
                throw new Exception();
            }
            $this->bean->invoice->name = $nextbillingnumber;
        }
        $this->bean->invoice->fy = Flight::setting()->fiscalyear;
        $this->bean->invoice->company = $csb->company;
        $this->bean->invoice->bookingdate = date('Y-m-d H:i:s');
        // copy to invoice from this deliverer
        $this->bean->invoice->import($this->bean->export(), 'totalnet, totalcost, subtotalnet, vatvalue, totalgros');
        $this->bean->invoice->person = $this->bean->person;
        $this->bean->invoice->kind = 0;//depends on the kind of invoice. 0 = Slaughter, 1 = other
        $this->bean->invoice->dateofslaughter = $csb->pubdate;
        $this->bean->invoice->vat = $this->bean->person->vat;
        // end of establishing a new invoice
        // transport to that person of interest
        $this->bean->transport();
        // end of transport
        return null;
    }
    
    /**
     * The attached invoice will be transferred to the person (company).
     *
     * @return void
     */
    public function transport()
    {
        switch ( $this->bean->person->billingtransport ) {
            case 'email':
                error_log('email to ' . $this->bean->person->email);
                break;

            case 'print':
                error_log('print to printer');
                break;

            case 'both':
                error_log('email and print');
                break;
            
            default:
                # code...
                break;
        }
    }
    
    /**
     * Calculates the prices of all stock that belongs to this deliverer of the given csb bean and
     * returns an array with a summery of the calculation.
     *
     * @param RedBean_OODBBean $csb
     * @return array
     */
    public function calculation($csb)
    {
        $stocks = R::find('stock', " csb_id = ? AND earmark = ? ORDER BY weight ", array(
            $csb->getId(),
            $this->bean->earmark
        ));
        if ( ! $pricing = $this->bean->person->pricing) {
            throw new Exception_Missingpricemask( $this->bean->supplier );
        }
        $ret = array(
            'totalnet' => 0,
            'totalnetlanuv' => 0,
            'totalweight' => 0,
            'totalmfa' => 0,
            'hasmfacount' => 0,
            'piggery' => 0
        );
        foreach ($stocks as $id => $stock) {
            $stock->calculation($this->bean, $pricing);
            $ret['totalnet'] += $stock->totaldprice;
            $ret['totalnetlanuv'] += $stock->totallanuvprice;
            $ret['totalweight'] += $stock->weight;
            $ret['totalmfa'] += $stock->mfa;
            if ($stock->mfa) $ret['hasmfacount']++;
            $ret['piggery']++;
        }
        $this->bean->calcdate = date('Y-m-d H:i:s');
        R::storeAll($stocks);
        return $ret;
    }
}
