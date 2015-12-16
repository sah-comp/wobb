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
 * Analysis model.
 *
 * A analysis model manages the company internal statistic.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Analysis extends Model
{
    /**
      * Define the lower margin for stock.
      */
    const LOWER_MARGIN = 80.0;
    
    /**
      * Define the upper margin for stock.
      */
    const UPPER_MARGIN = 110.0;

    /**
     * Holds the qualities (Handelsklasse) of stock to pick up in a summary.
     *
     * @var array
     */
    protected $qualities = array(
        'S', 'E', 'U', 'R', 'O', 'P'
    );
    
    /**
     * Holds the non-qualities (Handelsklasse) of stock to pick up in a summary.
     *
     * @var array
     */
    protected $nonQualities = array(
        'M', 'V'
    );

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
                'name' => 'company_id',
                'sort' => array(
                    'name' => 'company.name'
                ),
                'callback' => array(
                    'name' => 'getCompanyName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'startdate',
                'sort' => array(
                    'name' => 'startdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
            array(
                'name' => 'enddate',
                'sort' => array(
                    'name' => 'enddate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
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
		LEFT JOIN company ON company.id = lanuv.company_id
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
     * Returns the week of year from a given attribute.
     *
     * @param string Name of the attribute
     * @return int Week of the year
     */
    public function weekOfYear($attr = 'startdate')
    {
        $date = new DateTime($this->bean->$attr);
        return $date->format("W");
    }
    
    /**
     * Generate report for this lanuv bean.
     *
     * @param float $lowerMargin
     * @param float $upperMargin
     * @return void
     */
    public function generateReport($lowerMargin = self::LOWER_MARGIN, $upperMargin = self::UPPER_MARGIN)
    {
        $this->bean->ownAnalysisitem = array();
        // Qualities with weight margins
        foreach ($this->qualities as $quality) {
            $summary = $this->getSummaryQuality($quality, $lowerMargin, $upperMargin); // totals and averages of the stock
            $analysisitem = R::dispense('analysisitem');
            $analysisitem->quality = $quality;
            $analysisitem->piggery = $summary['piggery'];
            $analysisitem->sumweight = $summary['sumweight'];
            $analysisitem->sumtotaldprice = $summary['sumtotaldprice'];
            $analysisitem->sumtotallanuvprice = $summary['sumtotallanuvprice'];
            $analysisitem->avgmfa = $summary['avgmfa'];
            $analysisitem->avgprice = $summary['avgprice'];
            $analysisitem->avgpricelanuv = $summary['avgpricelanuv'];
            $analysisitem->avgweight = $summary['avgweight'];
            $analysisitem->avgdprice = $summary['avgdprice'];
            $this->bean->ownAnalysisitem[] = $analysisitem;
        }
        // Non-Qualities without weight margins
        foreach ($this->nonQualities as $quality) {
            $summary = $this->getSummaryNonQuality($quality); // totals and averages of the stock
            $analysisitem = R::dispense('analysisitem');
            $analysisitem->quality = $quality;
            $analysisitem->piggery = $summary['piggery'];
            $analysisitem->sumweight = $summary['sumweight'];
            $analysisitem->sumtotallanuvprice = $summary['sumtotallanuvprice'];
            $analysisitem->sumtotaldprice = $summary['sumtotaldprice'];
            $analysisitem->avgmfa = $summary['avgmfa'];
            $analysisitem->avgprice = $summary['avgprice'];
            $analysisitem->avgpricelanuv = $summary['avgpricelanuv'];
            $analysisitem->avgweight = $summary['avgweight'];
            $analysisitem->avgdprice = $summary['avgdprice'];
            $this->bean->ownAnalysisitem[] = $analysisitem;
        }
        return true;
    }
    
    /**
     * Returns an array with information about a certain stock quality.
     * Stock beans with attribute damage1 = '02' are collected.
     *
     * @param string $quality
     * @param float $margin_lo
     * @param float $margin_hi
     * @return array
     */
    public function getSummaryQuality($quality, $margin_lo, $margin_hi)
    {
		$sql = <<<SQL
        SELECT 
            count(id) as piggery, 
            sum(weight) as sumweight,
            avg(mfa) as avgmfa,
            sum(totaldprice) as sumtotaldprice,
            sum(totallanuvprice) as sumtotallanuvprice,
            (sum(totaldprice) / sum(weight)) as avgprice,
            (sum(totallanuvprice) / sum(weight)) as avgpricelanuv,
            avg(weight) as avgweight,
            avg(dprice) as avgdprice
        FROM stock 
        WHERE 
            buyer = :buyer AND 
            quality = :quality AND 
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            (weight >= :lo AND weight <= :hi) AND 
            (damage1 = '' OR damage1 = '02') AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':quality' => $quality,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate,
            ':lo' => $margin_lo,
            ':hi' => $margin_hi
        ));
    }
    
    /**
     * Returns an array with information about a certain stock non-quality.
     *
     * @param string $quality
     * @return array
     */
    public function getSummaryNonQuality($quality)
    {
		$sql = <<<SQL
        SELECT 
            count(id) as piggery, 
            sum(weight) as sumweight,
            avg(mfa) as avgmfa,
            sum(totaldprice) as sumtotaldprice,
            sum(totallanuvprice) as sumtotallanuvprice,
            (sum(totaldprice) / sum(weight)) as avgprice,
            (sum(totallanuvprice) / sum(weight)) as avgpricelanuv,
            avg(weight) as avgweight,
            avg(dprice) as avgdprice
        FROM stock 
        WHERE 
            buyer = :buyer AND 
            quality = :quality AND 
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            (damage1 = '' OR damage1 = '02') AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':quality' => $quality,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
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
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('startdate', array(
            new Converter_MysqlDate()
        ));
        $this->addConverter('enddate', array(
            new Converter_MysqlDate()
        ));
    }
    
    
    /**
     * update.
     *
     * This will check for file uploads.
     */
    public function update()
    {
        if ($this->bean->company_id) {
            $this->bean->company = R::load('company', $this->bean->company_id);
        } else {
            unset($this->bean->company);
        }
        parent::update();
    }
}
