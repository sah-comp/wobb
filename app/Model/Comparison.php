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
 * Comparion model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Comparison extends Model
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
        return strftime("%A, %e. %B %Y <span class=\"week\">Woche %V</span>", strtotime($this->bean->startdate));
    }


    /**
     * Returns the latest comparison bean.
     *
     * @return $comparison
     */
    public function getLatest()
    {
        if (! $latest = R::findOne('comparison', " ORDER BY startdate DESC LIMIT 1 ")) {
            $latest = R::dispense('comparison');
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
        return I18n::__('comparion_h1_' . $label);
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
                'name' => 'startdate',
                'sort' => array(
                    'name' => 'comparison.startdate'
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
                    'name' => 'comparison.piggery'
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
        LEFT JOIN company ON company.id = comparison.company_id
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
     * Compare things
     * @return bool
     */
    public function compare()
    {
        $summary = $this->getSummary($this->bean->person);
        $this->bean->piggery = $summary['piggery'];
        $this->bean->totalweight = $summary['sumweight'];
        $this->bean->meanmfa = $summary['avgmfa'];
        $this->bean->meanweight = $summary['avgweight'];
        $this->bean->totalnet = $summary['sumtotalpricenet'];
        $this->bean->avgprice = $summary['avgpricenet'];
        $this->bean->diff = 0; // no difference to the original calculation possible
        // now do the calculation of these stock with conditions of the deliverers to be compared to
        $this->calculation();
        return true;
    }

    /**
     * Returns an array with information about stock of the given deliverer.
     *
     * @param string $person
     * @return array
     */
    public function getSummary($person)
    {
        $sql = <<<SQL
        SELECT
            count(id) AS piggery,
            sum(itw) AS sumitw,
            sum(weight) AS sumweight,
            avg(mfa) AS avgmfa,
            sum(totaldprice) AS sumtotaldprice,
            sum(totallanuvprice) AS sumtotallanuvprice,
            sum(totaldpricenet) AS sumtotalpricenet,
            sum(totaldpricenetitw) AS sumtotalpricenetitw,
            (sum(totaldprice) / sum(weight)) AS avgprice,
            (sum(totaldpricenet) / sum(weight)) AS avgpricenet,
            (sum(totaldpricenetitw) / sum(weight)) AS avgpricenetitw,
            (sum(totallanuvprice) / sum(weight)) AS avgpricelanuv,
            avg(weight) AS avgweight,
            avg(dprice) AS avgdprice
        FROM stock
        WHERE
            buyer = :buyer AND
            person_id = :pid AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':pid' => $person->getId(),
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }

    /**
     * Calculates averages and sums based on the deliverers data using a fake pig.
     *
     * @return void
     */
    public function calculation()
    {
        $stocks = R::find('stock', " (pubdate >= :startdate AND pubdate <= :enddate) AND person_id = :pid AND csb_id IS NOT NULL", [
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate,
            ':pid' => $this->bean->person->getId()
        ]);
        foreach ($this->bean->ownDeliverer as $id => $deliverer) {
            $count = 0;
            $deliverer->totalnet = 0;
            $deliverer->deliverer = $deliverer; // funny, but stock calculation needs a parent
            $deliverer->sprice = $deliverer->dprice = $this->bean->baseprice + $deliverer->person->reldprice;
            $pricing = $deliverer->person->pricing;
            foreach ($stocks as $id => $stock) {
                //$count++;
                //error_log($count . ' Compare ' . $deliverer->person->nickname . ' stock ' . $stock->name);
                $stock->calculation($deliverer, $pricing, true);
                $deliverer->totalnet += $stock->totaldpricenet;
            }
            //error_log('Comparing deliverer #' . $deliverer->getId() . ' Net ' . $deliverer->totalnet . ' - ' . $this->bean->totalnet);
            $deliverer->diff = round($deliverer->totalnet, 2) - round($this->bean->totalnet, 2);
            $deliverer->avgprice = $deliverer->totalnet / $this->bean->totalweight;
            $deliverer->deliverer = null;
        }
        return true;
    }

    /**
     * dispense a new comparison bean.
     */
    public function dispense()
    {
        $this->bean->piggery = 0;
        $this->bean->itwpiggery = 0;
        $this->bean->diff = 0;
        $this->bean->season = 0; // 0 = winter, 1 = summer
        $this->bean->startdate = date('Y-m-d');
        $this->bean->enddate = date('Y-m-d');
        $this->addConverter(
            'startdate',
            new Converter_Mysqldate()
        );
        $this->addConverter(
            'enddate',
            new Converter_Mysqldate()
        );
        $this->addConverter('baseprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalnet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgprice', array(
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
        $this->addConverter('diff', array(
            new Converter_Decimal()
        ));
        $this->addValidator('startdate', array(
            new Validator_HasValue()
        ));
        $this->addValidator('enddate', array(
            new Validator_HasValue()
        ));
        $this->addValidator('company_id', array(
            new Validator_HasValue()
        ));
    }

    /**
     * Checks if the comparisons given date is within summer or winter season.
     * Define seasons time periods in config.php.
     *
     * @return int
     */
    public function whichSeason()
    {
        $ts = strtotime($this->bean->startdate);
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
        parent::update();
        $this->bean->season = $this->whichSeason();
    }

    /**
     * Returns an array of deliverers ordered by person and invoice.
     *
     * @return array
     */
    public function getDeliverers()
    {
        return $this->bean->with(' ORDER BY @person.nickname ')->ownDeliverer;
    }
}
