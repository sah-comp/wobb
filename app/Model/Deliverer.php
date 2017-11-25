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
     * Returns 'mailed' when sent flag is true, otherwise an empty string is returned.
     *
     * @return string
     */
    public function wasSent()
    {
        if ( $this->bean->sent ) return 'mailed';
        return '';
    }

    /**
     * Returns true when this beans person has either billingtransport set to email or both.
     *
     * @return bool
     */
    public function wantsInvoiceAsEmail()
    {
        if ( $this->bean->person->billingtransport == 'email' || $this->bean->person->billingtransport == 'both' ) return true;
        return false;
    }

    /**
     * Returns true when this beans person billingtransport is mail only.
     *
     * @return bool
     */
    public function wantsEmail()
    {
        if ( $this->bean->person->billingtransport == 'email' ) return true;
        return false;
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
     * Returns notes about the determination of the base price.
     *
     * @return bool
     */
    public function getInfoAboutDealerPrice()
    {
        return $this->bean->person->noterelprice;
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
            $this->bean->person->account,
            $this->bean->person->nickname,
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
      if ( $this->bean->calcdate === NULL || $this->bean->calcdate == '0000-00-00 00:00:00' ) return FALSE;
      return TRUE;
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
     * Calculates conditions and costs of this deliverer with given stock bean and returns total mix.
     *
     * @param RedBean_OODBBean $stock
     * @return float
     */
    public function calculate(RedBean_OODBBean $stock)
    {
        if ( ! $this->bean->person) return (float)0;
        $mix = 0;
        $mix += $this->calculateCondition($stock);
        $mix += $this->calculateCost($stock);
        return (float)$mix;
    }

    /**
     * Calculates conditions of this deliverer with given stock bean and returns the total bonus.
     *
     * @param RedBean_OODBBean $stock
     * @return float
     */
    protected function calculateCondition(RedBean_OODBBean $stock)
    {
        $conditions = $this->bean->person->ownCondition; // fetch it from the person
        $bonus = 0;
        $stock->bonusitem = 0;
        $stock->bonusweight = 0;
        if ( count ($conditions) == 0) return (float)0.00;
        foreach ($conditions as $id => $condition) {
            switch ( $condition->label ) {
                case 'stockperitem':
                    $bonus += $condition->value;
                    $stock->bonusitem += $condition->value;
                    break;

                case 'stockperweight':
                    $bonus += $stock->weight * $condition->value;
                    $stock->bonusweight += $condition->value;
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
     * Calculates cost of this deliverer with given stock bean and returns the total cost.
     *
     * @param RedBean_OODBBean $stock
     * @return float
     */
    protected function calculateCost(RedBean_OODBBean $stock)
    {
        $costs = $this->bean->person->ownCost; // fetch it from the person
        $cost_sum = 0;
        $stock->costitem = 0;
        $stock->costweight = 0;
        if ( count ($costs) == 0) return (float)0.00;
        foreach ($costs as $id => $cost) {
            switch ( $cost->label ) {
                case 'stockperitem':
                    $cost_sum += $cost->value;
                    $stock->costitem += $cost->value;
                    break;

                case 'stockperweight':
                    $cost_sum += $stock->weight * $cost->value;
                    $stock->costweight += $cost->value;
                    break;

                default:
                    // dunno?! nothing.
                    break;
            }
        }
        $stock->cost = $cost_sum;
        return (float)$cost_sum;
    }

    /**
     * Returns an array with special prices for damaged stock.
     *
     * If no specialprice beans for this deliverer exists yet they will be created.
     * There are three sections of specialprices. One is for damage1, the second for
     * damage2 and another for certain qualities.
     * A specialprice can also have a number of costs connected to it. E.g. a damage1
     * of '08', a 'Kotelettschaden' will be charged with 'Attestkosten' of 4.10 Euros.
     *
     * @return array
     */
    public function getSpecialPrices()
    {
        if ( ! $this->bean->ownSpecialprice) {
            // damage1
            $stocks = R::getAll("SELECT COUNT(id) AS total, damage1 FROM stock WHERE csb_id = ? AND supplier = ? AND damage1 !='' GROUP BY damage1 ORDER BY damage1 ", array($this->bean->csb->getId(), $this->bean->supplier));
            foreach ($stocks as $id => $stock) {

                if ( ! $var = R::findOne('var', " (( name = :damage1 AND supplier = :supplier ) OR ( name = :damage1 AND supplier = '')) AND kind = 'damage1' ORDER BY supplier DESC LIMIT 1 ", array(
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
                foreach ($var->ownCost as $id => $cost) {
                    $scost = R::dispense('scost');
                    $scost->import($cost->export(), 'label,content,value');
                    $price->ownScost[] = $scost;
                }
                $this->bean->ownSpecialprice[] = $price;
            }

            // damage2
            $stocks = R::getAll("SELECT COUNT(id) AS total, damage2 FROM stock WHERE csb_id = ? AND supplier = ? AND damage2 !='' GROUP BY damage2 ORDER BY damage2 ", array($this->bean->csb->getId(), $this->bean->supplier));
            foreach ($stocks as $id => $stock) {

                if ( ! $var = R::findOne('var', " (( name = :damage2 AND supplier = :supplier ) OR ( name = :damage2 AND supplier = '')) AND kind = 'damage2' ORDER BY supplier DESC LIMIT 1 ", array(
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
                foreach ($var->ownCost as $id => $cost) {
                    $scost = R::dispense('scost');
                    $scost->import($cost->export(), 'label,content,value');
                    $price->ownScost[] = $scost;
                }
                $this->bean->ownSpecialprice[] = $price;
            }

            $qualities = R::find('var', " kind='quality' AND supplier = ''");
            foreach ($qualities as $id => $quality) {
                // quality
                $stocks = R::getAll("SELECT COUNT(id) AS total, quality FROM stock WHERE csb_id = ? AND supplier = ? AND quality = ? GROUP BY quality ORDER BY quality ", array($this->bean->csb->getId(), $this->bean->supplier, $quality->name));
                foreach ($stocks as $id => $stock) {

                    if ( ! $var = R::findOne('var', " (( name = :quality AND supplier = :supplier ) OR ( name = :quality AND supplier = '')) AND kind = 'quality' ORDER BY supplier DESC LIMIT 1 ", array(
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
                    foreach ($var->ownCost as $id => $cost) {
                        $scost = R::dispense('scost');
                        $scost->import($cost->export(), 'label,content,value');
                        $price->ownScost[] = $scost;
                    }
                    $this->bean->ownSpecialprice[] = $price;
                }
            }
        }
        return $this->bean->ownSpecialprice;
    }

    /**
     * Generates an invoice for this deliverer for the given slaughterday csb bean.
     *
     * @param RedBean_OODBBean $csb
     * @throws Exception if no billing number can be generated
     * @return void
     */
    public function billing(RedBean_OODBBean $csb)
    {
        if ( ! $this->bean->invoice()->name ) {
            if ( ! $nextbillingnumber = $csb->company->nextBillingnumber() ) {
                throw new Exception();
            }
            $this->bean->invoice->name = $nextbillingnumber;
            $this->bean->invoice->fy = Flight::setting()->fiscalyear;
            $this->bean->invoice->bookingdate = date('Y-m-d H:i:s');
            $this->bean->invoice->instructed = false;//instructed to pay
            $this->bean->invoice->paid = false;//not yet paid
            $this->bean->invoice->canceled = false;//storno
            $this->bean->invoice->duedate = date('Y-m-d', strtotime(
                $this->bean->invoice->bookingdate . ' +' . $this->bean->person->timeforpay . 'days'
            ));
        }
        $this->bean->invoice->paid = false;//not yet paid
        $this->bean->invoice->instructed = false;//instructed to pay
        $this->bean->invoice->company = $csb->company;
        $this->bean->invoice->person = $this->bean->person;
        $this->bean->invoice->vat = $this->bean->person->vat;
        $this->bean->invoice->totalnet = $this->bean->totalnet;
        $bonusnet = 0;
        foreach ($this->bean->person->ownCondition as $id => $condition) {
            if ( $condition->doesnotaffectinvoice ) continue;//skip condition
            if ( $condition->label == 'stockperitem' ) {
                $bonusnet += $this->bean->piggery * $condition->value;
            } elseif ( $condition->label == 'stockperweight' ) {
                $bonusnet += $this->bean->totalweight * $condition->value;
            }
        }
        $costnet = 0;
        foreach ($this->bean->person->ownCost as $id => $cost) {
            if ( $cost->label == 'stockperitem' ) {
                $costnet += $this->bean->piggery * $cost->value;
            } elseif ( $cost->label == 'stockperweight' ) {
                $costnet += $this->bean->totalweight * $cost->value;
            } elseif ( $cost->label == 'flat' ) {
                $costnet += $cost->value;
            }
        }
        $this->bean->invoice->bonusnet = $bonusnet;
        $this->bean->invoice->costnet = $costnet;
        $this->bean->invoice->subtotalnet = $this->bean->invoice->totalnet;
        $this->bean->invoice->subtotalnet += $this->bean->invoice->bonusnet;
        $this->bean->invoice->subtotalnet -= $this->bean->invoice->costnet;
        // set special net value attributes according to vat setting
        if ( $this->bean->invoice->vat->getId() == Flight::setting()->vatfarmer ) {
            $this->bean->invoice->totalnetfarmer = $this->bean->invoice->subtotalnet;
            $this->bean->invoice->totalnetnormal = 0;
            $this->bean->invoice->totalnetother = 0;
        } elseif ( $this->bean->invoice->vat->getId() == Flight::setting()->vatnormal ) {
            $this->bean->invoice->totalnetfarmer = 0;
            $this->bean->invoice->totalnetnormal = $this->bean->invoice->subtotalnet;
            $this->bean->invoice->totalnetother = 0;
        } else {
            $this->bean->invoice->totalnetfarmer = 0;
            $this->bean->invoice->totalnetnormal = 0;
            $this->bean->invoice->totalnetother = $this->bean->invoice->subtotalnet;
        }
        $this->bean->invoice->vatvalue =
                round($this->bean->invoice->subtotalnet * $this->bean->invoice->vat->value / 100, 2);
        $this->bean->invoice->totalgros =
                                $this->bean->invoice->subtotalnet + $this->bean->invoice->vatvalue;

        $this->bean->invoice->kind = 0;//depends on the kind of invoice. 0 = Slaughter, 1 = other
        $this->bean->invoice->dateofslaughter = $csb->pubdate;
        // end of establishing a new invoice
        $this->dispatchBillingNumberToStock($csb);
        return null;
    }

    /**
     * Dispatches the billingnumber (invoice->name) of this deliverer of the given csb to its stock.
     *
     * @param RedBean_OODBBean $csb
     * @return void
     */
    public function dispatchBillingNumberToStock($csb)
    {
        $sql = "UPDATE stock SET billnumber = :billnumber WHERE supplier = :supplier AND csb_id = :csb_id";
        R::exec($sql, array(
            ':billnumber' => $this->bean->invoice->name,
            ':supplier' => $this->bean->supplier,
            ':csb_id' => $csb->getId()
        ));
        return null;
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
            'totalnetsprice' => 0,
            'totalnetlanuv' => 0,
            'totalweight' => 0,
            'totalmfa' => 0,
            'hasmfacount' => 0,
            'piggery' => 0
        );
        foreach ($stocks as $id => $stock) {
            $stock->calculation($this->bean, $pricing);
            $ret['totalnet'] += $stock->totaldprice;
            $ret['totalnetsprice'] += $stock->totalsprice;
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
