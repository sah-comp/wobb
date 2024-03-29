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
 * Adjustmentitem model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Adjustmentitem extends Model
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
        );
    }

    /**
     * Calculation of this bean.
     *
     * @param $adjustment
     * @return void
     */
    public function calculation($adjustment)
    {
        if ( ! $this->bean->vat || ! $this->bean->vat->getId() ) {
            $this->bean->vat = $this->bean->person->vat;
        }
        $this->bean->vatvalue = $this->bean->net * $this->bean->vat->value / 100;
        $this->bean->gros = $this->bean->net + $this->bean->vatvalue;
        $this->bean->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated this bean
    }

    /**
     * Returns wether the adjustmentitem was already calculated or not.
     *
     * @return bool
     */
    public function wasCalculated()
    {
      if ( $this->bean->calcdate === NULL || $this->bean->calcdate == '0000-00-00 00:00:00' ) return FALSE;
      return TRUE;
    }

    /**
     * Billing of this bean.
     *
     * @param $adjustment
     * @return array with net, vat value and gros
     */
    public function billing($adjustment)
    {
        if ( ! $this->bean->invoice()->name ) {
            if ( ! $nextbillingnumber = $adjustment->company->nextBillingnumber() ) {
                throw new Exception();
            }
            $this->bean->invoice->name = $nextbillingnumber;
            $this->bean->invoice->fy = Flight::setting()->fiscalyear;
            $this->bean->invoice->bookingdate = date('Y-m-d H:i:s');
            $this->bean->invoice->canceled = false;//storno
            $this->bean->invoice->duedate = date('Y-m-d', strtotime(
                $this->bean->invoice->bookingdate . ' +' . $this->bean->person->timeforpay . 'days'
            ));
        }
        $this->bean->invoice->paid = false;//not yet paid
        $this->bean->invoice->instructed = false;//instructed to pay
        $this->bean->invoice->company = $adjustment->company;
        $this->bean->invoice->person = $this->bean->person;
        $this->bean->invoice->vat = $this->bean->vat;
        $this->bean->invoice->totalnet = $this->bean->net;
        $this->bean->invoice->bonusnet = 0;
        $this->bean->invoice->costnet = 0;
        $this->bean->invoice->subtotalnet = $this->bean->invoice->totalnet;
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
        $this->bean->invoice->vatvalue = $this->bean->vatvalue;
        $this->bean->invoice->totalgros = $this->bean->gros;
        $this->bean->invoice->kind = 1;//depends on the kind of invoice. 0 = Slaughter, 1 = other
        $this->bean->invoice->dateofslaughter = $adjustment->pubdate;
        $this->bean->billingdate = date('Y-m-d H:i:s'); //stamp that we have calculated this bean
        return array(
            'net' => $this->bean->invoice->subtotalnet,
            'vatvalue' => $this->bean->invoice->vatvalue,
            'gros' => $this->bean->invoice->totalgros
        );
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
     * Returns wether the adjustmentitem was already billed or not.
     *
     * @return bool
     */
    public function wasBilled()
    {
      if ( $this->bean->billingdate === NULL || $this->bean->billingdate == '0000-00-00 00:00:00' ) return FALSE;
      return TRUE;
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
     * Returns the invoice bean of this bean.
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
     * Update.
     */
    public function update()
    {
        if ($this->bean->person_id) {
            $this->bean->person = R::load('person', $this->bean->person_id);
        } else {
            unset($this->bean->person);
        }
        if ($this->bean->vat_id) {
            $this->bean->vat = R::load('vat', $this->bean->vat_id);
        } else {
            $this->bean->vat = $this->bean->person->vat;
        }
        parent::update();
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->bean->delinv = '';
        $this->bean->billingdate = NULL;//'0000-00-00 00:00:00';
        $this->bean->calcdate = NULL;//'0000-00-00 00:00:00';
		$this->bean->deldate = NULL;//'0000-00-00 00:00:00';
		$this->bean->person = R::dispense('person');
        $this->addConverter('billingdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('deldate',
            new Converter_Mysqldate()
        );
        $this->addConverter('calcdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('net', array(
            new Converter_Decimal()
        ));
        $this->addConverter('vatvalue', array(
            new Converter_Decimal()
        ));
        $this->addConverter('gros', array(
            new Converter_Decimal()
        ));
    }
}
