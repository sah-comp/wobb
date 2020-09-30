<?php
/**
 * Cinnebar.
 *
 * My lightweight no-framework framework written in PHP.
 *
 * @package Cinnebar
 * @author $Author$
 * @version $Id$
 */

/**
 * Plan model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Plan extends Model
{
    /**
     * Map to translate media extension to mime type.
     *
     * @var array
     */
    protected $extensions = array(
        'txt' => 'text/plain'
    );

    /**
     * Returns the media file name.
     *
     * @return string
     */
    public function getPrintableName()
    {
        return $this->bean->sanename;
    }

    /**
     * Returns a string with nicely formatted date of slaughter.
     *
     * It's a happy date, isn't it? Not for the poor piggy, my dear.
     *
     * @return string
     */
    public function getDateOfSlaughter()
    {
        Flight::setlocale();
        return strftime("%A, %e. %B %Y <span class=\"week\">Woche %V</span>", strtotime($this->bean->pubdate));
    }


    /**
     * Returns the latest plan bean.
     *
     * @return $plan
     */
    public function getLatest()
    {
        if (! $latest = R::findOne('plan', " ORDER BY pubdate DESC LIMIT 1 ")) {
            $latest = R::dispense('plan');
        }
        return $latest;
    }

    /**
     * Returns a string to be used as a headline.
     *
     * @param string $label
     * @return string
     */
    public function getHeadline($label = 'calculation')
    {
        return I18n::__('planning_h1_' . $label);
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
                'name' => 'pubdate',
                'sort' => array(
                    'name' => 'plan.pubdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
            array(
                'name' => 'baseprice',
                'sort' => array(
                    'name' => 'baseprice'
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
                'name' => 'piggery',
                'sort' => array(
                    'name' => 'plan.piggery'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
                )
            )
        );
    }

    /**
     * Returns SQL string.
     *
     * @param string (optional) $fields to select
     * @param string (optional) $where
     * @param string (optional) $order
     * @param int (optional) $offset
     * @param int (optional) $limit
     * @return string $sql
     */
    public function getSql($fields = 'id', $where = '1', $order = null, $offset = null, $limit = null)
    {
        $sql = <<<SQL
		SELECT
		    {$fields}
		FROM
		    {$this->bean->getMeta('type')}
		LEFT JOIN company ON company.id = plan.company_id
		WHERE
		    {$where}
SQL;
        //add optional order by
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        //add optional limit
        if ($offset || $limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
        return $sql;
    }

    /**
     * Returns the name of this beans company.
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->bean->company->name;
    }
	
    /**
     * Calculates averages and sums based on the deliverers data using a fake pig.
     *
     * @return void
     */
    public function calculation()
    {
		$period = [
			'start' => date('Y-m-d', strtotime($this->bean->pubdate . ' - ' . $this->bean->period . ' weeks')), 
			'end' => $this->bean->pubdate
		];
		$this->bean->piggery = 0;
		$this->bean->totalweight = 0;
		$this->bean->totalnet = 0;
		$this->bean->meanweight = 0;
		$this->bean->meandprice = 0;
		$totalmfa = 0;
		$count = count($this->bean->ownDeliverer);
        foreach ($this->bean->ownDeliverer as $_id => $deliverer) {
			$deliverer->supplier = $deliverer->person->nickname;
			$averages = $this->bean->getAverages($deliverer->person->nickname, $period);
			if (! $deliverer->dprice) {
				if ($deliverer->person->nextweekprice && $this->bean->nextweekprice) {
					$deliverer->dprice = $this->bean->nextweekprice + $deliverer->person->reldprice;
					$deliverer->sprice = $this->bean->nextweekprice + $deliverer->person->relsprice;				
				} else {
					$deliverer->dprice = $this->bean->baseprice + $deliverer->person->reldprice;
					$deliverer->sprice = $this->bean->baseprice + $deliverer->person->relsprice;
				}
			}
			$deliverer->meanmfa = $averages['meanmfa'];
			$deliverer->meanweight = $averages['meanweight'];
			$deliverer->calcdate = date('Y-m-d H:i:s');
			
			$stock = R::dispense('stock');
			$pricing = $deliverer->person->pricing;
			
			$stock->mfa = $deliverer->meanmfa;
			$stock->weight = $deliverer->meanweight;
			
			$lanuv_tax = $deliverer->calculate($stock);
			$stock->calculatePrice($deliverer, $pricing, $lanuv_tax);
			$netvalue =  ($stock->weight * $stock->dprice) + $stock->bonus - $stock->cost;
			$deliverer->meandprice = round($netvalue / $deliverer->meanweight, 3);
			$deliverer->totalnet = round($deliverer->meanweight * $deliverer->meandprice * $deliverer->piggery, 3);
			
			$this->bean->piggery += $deliverer->piggery;
			$this->bean->totalweight += round($deliverer->piggery * $deliverer->meanweight, 3);
			$this->bean->totalnet += $deliverer->totalnet;
			$totalmfa += $deliverer->meanmfa;
		}
		$this->bean->meanweight = round($this->bean->totalweight / $this->bean->piggery, 3);
		$this->bean->meandprice = round($this->bean->totalnet / $this->bean->totalweight, 3);
		$this->bean->meanmfa = round($totalmfa / $count, 3);
		return true;
	}
	
	/**
	 * Returns an array with averages of mfa and weight.
	 *
	 * @param string $supplier
	 * @param array $period
	 * @return array
	 */
	public function getAverages($supplier, array $period)
	{
		return R::getRow("SELECT AVG(mfa) AS meanmfa, ROUND(AVG(weight), 2) AS meanweight FROM stock WHERE buyer = ? AND supplier = ? AND (pubdate >= ? AND pubdate <= ?) AND csb_id IS NOT NULL", [$this->bean->company->buyer, $supplier, $period['start'], $period['end']]);
	}

    /**
     * dispense a new plan bean.
     */
    public function dispense()
    {
        $this->bean->piggery = 0;
		$this->bean->season = 0; // 0 = winter, 1 = summer
        $this->bean->pubdate = date('Y-m-d');
        $this->bean->period = 6; //weeks to look back for averages
        $this->addConverter(
            'pubdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('baseprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('nextweekprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sowprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalnet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('meanweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('meandprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('meanmfa', array(
            new Converter_Decimal()
        ));
        $this->addValidator('pubdate', array(
            new Validator_HasValue()
        ));
        $this->addValidator('company_id', array(
            new Validator_HasValue()
        ));
    }
	
	/**
	 * Checks if the plans given date is within summer or winter season.
	 * Define seasons time periods in config.php.
	 *
	 * @return int
	 */
	public function whichSeason()
	{
		$ts = strtotime($this->bean->pubdate);
		$seasons = Flight::get('seasons');
		$summer_start = strtotime(date('Y', $ts) . '-' . $seasons['summer']['start']);
		$summer_end = strtotime(date('Y', $ts) . '-' . $seasons['summer']['end']);
		if (($ts >= $summer_start) && ($ts <= $summer_end)) {
			return 1; //summer
		}
		return 0; //winter
	}

    /**
     * update.
     *
     * This will check for file uploads.
     */
    public function update()
    {
		$this->bean->season = $this->whichSeason();
        parent::update();
    }
	
	/**
	 * Returns an array of deliverers ordered by person and invoice.
	 *
	 * @return array
	 */
	public function getDeliverers()
	{
		return $this->bean->with(' ORDER BY supplier, earmark ')->ownDeliverer;
	}
}
