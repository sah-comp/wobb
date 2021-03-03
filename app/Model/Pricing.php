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
 * Pricing model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Pricing extends Model
{
    /**
     * Calculate the agio and disagio of a stock bean with this pricing.
     *
     * @todo I say, it's truly all the same, so implement a loop for weight and mfa
     *
     * @param $stock
     * @param $deliverer
     * @return bool
     */
    public function calculate($stock, $deliverer)
    {
        //$stock->agio = 0;
        //$stock->disagio = 0;
        $optimalWeightMargin = $this->getOptimalMargin('weight');
        $mfaMarginKind = 'mfa'; // use mfa kind for mfa margins
        if (($stock->weight >= $optimalWeightMargin->lo and
              $stock->weight <= $optimalWeightMargin->hi)) {
            // optimal weight
        } elseif ($stock->weight < $optimalWeightMargin->lo) {
            // underweight
            $mfaMarginKind = 'mfasub'; // we have a underweight stock, use mfasub
            $lowerWeights = $this->getLowerMargins('weight', $optimalWeightMargin);
            foreach ($lowerWeights as $id => $lowerWeight) {
                $this->calculateAgioDisagoLo('weight', $stock, $lowerWeight);
            }
        } elseif ($stock->weight > $optimalWeightMargin->hi) {
            // overweight
            $overWeights = $this->getOverMargins('weight', $optimalWeightMargin);
            foreach ($overWeights as $id => $overWeight) {
                $this->calculateAgioDisagoHi('weight', $stock, $overWeight);
            }
        }
        // calculate mfa margins, using either mfa or mfasub if the stock is underweight
        $optimalMfaMargin = $this->getOptimalMargin($mfaMarginKind); // can be 'mfa' or 'mfasub'
        if (($stock->mfa >= $optimalMfaMargin->lo and
              $stock->mfa <= $optimalMfaMargin->hi)) {
            // optimal mfa
        } elseif ($stock->mfa < $optimalMfaMargin->lo) {
            // mfa lower than optimal
            $lowerMfas = $this->getLowerMargins($mfaMarginKind, $optimalMfaMargin);
            foreach ($lowerMfas as $id => $lowerMfa) {
                $this->calculateAgioDisagoLo('mfa', $stock, $lowerMfa);
            }
        } elseif ($stock->mfa > $optimalMfaMargin->hi) {
            // mfa higher than optimal
            $overMfas = $this->getOverMargins($mfaMarginKind, $optimalMfaMargin);
            foreach ($overMfas as $id => $overMfa) {
                $this->calculateAgioDisagoHi('mfa', $stock, $overMfa);
            }
        }
        return true;
    }

    /**
     * Calculates a difference and adjusts agio and disagion of the given stock when stock is low.
     *
     * @param string $field must be either 'weight' or 'mfa'
     * @param $stock
     * @param $margin
     * @return float
     */
    public function calculateAgioDisagoLo($field, $stock, $margin)
    {
        $diff = 0;
        if ($margin->op == '-') {
            if ($stock->$field < $margin->lo) {
                $diff = $margin->hi - $margin->lo;
            } elseif ($stock->$field >= $margin->lo && $stock->$field <= $margin->hi) {
                $diff = $margin->hi - $stock->$field;
            }
            $stock->disagio += $diff * $margin->value;
        } elseif ($margin->op == '+') {
            if ($stock->$field > $margin->hi) {
                $diff = $margin->hi - $margin->lo;
            } elseif ($stock->$field >= $margin->lo && $stock->$field <= $margin->hi) {
                $diff = $margin->hi - $stock->$field;
            }
            $stock->agio += $diff * $margin->value;
        }
        return (float)$diff;
    }

    /**
     * Calculates a difference and adjusts agio and disagion of the given stock when stock is high.
     *
     * @param string $field must be either 'weight' or 'mfa'
     * @param $stock
     * @param $margin
     * @return float
     */
    public function calculateAgioDisagoHi($field, $stock, $margin)
    {
        $diff = 0;
        if ($margin->op == '-') {
            if ($stock->$field > $margin->hi) {
                $diff = $margin->hi - $margin->lo;
            } elseif ($stock->$field >= $margin->lo && $stock->$field <= $margin->hi) {
                $diff = $stock->$field - $margin->lo;
            }
            $stock->disagio += $diff * $margin->value;
        } elseif ($margin->op == '+') {
            if ($stock->$field > $margin->hi) {
                $diff = $margin->hi - $margin->lo;
            } elseif ($stock->$field >= $margin->lo && $stock->$field <= $margin->hi) {
                $diff = $stock->$field - $margin->lo;
            }
            $stock->agio += $diff * $margin->value;
        }
        return (float)$diff;
    }


    /**
     * Returns a margin bean which holds the values for a optimal mfa stock.
     *
     * @param string $kind defaults to 'mfa'
     * @return $margin
     */
    public function getOptimalMargin($kind = 'mfa')
    {
        $margins = $this->bean->withCondition(" kind = ? AND op = '=' ", array(
            $kind
        ))->ownMargin;
        return reset($margins);
    }

    /**
     * Returns an array with margin beans that have lower mfa compared to given optimal mfa margin.
      *
      * @param string $kind defaults to 'mfa'
      * @param $optimalMargin
      * @return array
      */
    public function getLowerMargins($kind, $optimalMargin)
    {
        $margins = $this->bean->withCondition(" kind = ? AND lo < ? AND op != '=' ORDER BY lo DESC ", array($kind, $optimalMargin->lo))->ownMargin;
        return $margins;
    }

    /**
     * Returns an array with margin beans that have over mfa compared to given optimal mfa margin.
     *
     * @param string $kind defaults to 'mfa'
     * @param $optimalMargin
     * @return array
     */
    public function getOverMargins($kind, $optimalMargin)
    {
        $margins = $this->bean->withCondition(" kind = ? AND hi > ? AND op != '=' ORDER BY lo ASC ", array($kind, $optimalMargin->hi))->ownMargin;
        return $margins;
    }

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
                'name' => 'active',
                'sort' => array(
                    'name' => 'pricing.active'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                ),
                'width' => '5rem'
            )
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
    }
}
