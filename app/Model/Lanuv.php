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
 * Lanuv model.
 *
 * A lanuv model manages a certain statistic for the german authorities of
 * nature, surroundings and consumers following the FLGDV 1.
 *
 * @see http://www.gesetze-im-internet.de/flgdv_1/
 *
 * @todo Clean up this code according to Model_Analysis
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Lanuv extends Model
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
     * If the grand total of stock beans is zero an Exception is thrown.
     *
     * @param float $lowerMargin
     * @param float $upperMargin
     * @return void
     */
    public function generateReport($lowerMargin = self::LOWER_MARGIN, $upperMargin = self::UPPER_MARGIN)
    {
        $this->bean->ownLanuvitem = array();
        $summary = $this->getSummaryTotal($lowerMargin, $upperMargin);
        if ( $summary['piggery'] == 0 ) {
            throw new Exception('Grand total piggery is zero.');
        }
        $this->copyFromSummary(null, $this->bean, $summary, $summary['piggery']);
        // Qualities with weight margins
        foreach ($this->qualities as $quality) {
            $summary = $this->getSummaryQuality($quality, $lowerMargin, $upperMargin); // totals and averages of the stock
            $lanuvitem = R::dispense('lanuvitem');
            $this->copyFromSummary($quality, $lanuvitem, $summary, $this->bean->piggery);
            /*
            $lanuvitem->quality = $quality;
            $lanuvitem->piggery = $summary['piggery'];
            $lanuvitem->sumweight = $summary['sumweight'];
            $lanuvitem->sumtotaldprice = $summary['sumtotaldprice'];
            $lanuvitem->sumtotallanuvprice = $summary['sumtotallanuvprice'];
            $lanuvitem->avgmfa = $summary['avgmfa'];
            $lanuvitem->avgprice = $summary['avgprice'];
            $lanuvitem->avgpricelanuv = $summary['avgpricelanuv'];
            $lanuvitem->avgweight = $summary['avgweight'];
            $lanuvitem->avgdprice = $summary['avgdprice'];
            */
            $this->bean->ownLanuvitem[] = $lanuvitem;
        }
        // Non-Qualities without weight margins
        foreach ($this->nonQualities as $quality) {
            $summary = $this->getSummaryNonQuality($quality); // totals and averages of the stock
            $lanuvitem = R::dispense('lanuvitem');
            $this->copyFromSummary($quality, $lanuvitem, $summary, $this->bean->piggery);
            /*
            $lanuvitem->quality = $quality;
            $lanuvitem->piggery = $summary['piggery'];
            $lanuvitem->sumweight = $summary['sumweight'];
            $lanuvitem->sumtotallanuvprice = $summary['sumtotallanuvprice'];
            $lanuvitem->sumtotaldprice = $summary['sumtotaldprice'];
            $lanuvitem->avgmfa = $summary['avgmfa'];
            $lanuvitem->avgprice = $summary['avgprice'];
            $lanuvitem->avgpricelanuv = $summary['avgpricelanuv'];
            $lanuvitem->avgweight = $summary['avgweight'];
            $lanuvitem->avgdprice = $summary['avgdprice'];
            */
            $this->bean->ownLanuvitem[] = $lanuvitem;
        }
        $this->markAsReportedNoWeight($this->nonQualities);
        $this->markAsReportedWeight($this->qualities, $lowerMargin, $upperMargin);
        return true;
    }
    
    /**
     * Copies values from summary array into the given bean.
     *
     * @param string $quality or empty
     * @param RedBean_OODBBean $bean
     * @param array $summary
     * @param int $total
     * @return void
     */
    public function copyFromSummary($quality = '', RedBean_OODBBean $bean, array $summary = array(), $total)
    {
        //$bean->kind = 0; //this is a quality entry
        $bean->quality = $quality;
        $bean->piggery = $summary['piggery'];
        if ( $total != 0) {
            $bean->piggerypercentage = $summary['piggery'] * 100 / $total;
        } else {
            $bean->piggerypercentage = 0;
        }
        $bean->sumweight = $summary['sumweight'];
        $bean->sumtotaldprice = $summary['sumtotaldprice'];
        $bean->sumtotallanuvprice = $summary['sumtotallanuvprice'];
        $bean->avgmfa = $summary['avgmfa'];
        $bean->avgprice = $summary['avgprice'];
        $bean->avgpricelanuv = $summary['avgpricelanuv'];
        $bean->avgweight = $summary['avgweight'];
        $bean->avgdprice = $summary['avgdprice'];
        return true;
    }
    
    /**
     * Returns an array with information about totals.
     * Stock beans with attribute damage1 = '02' are collected.
     *
     * @param float $margin_lo
     * @param float $margin_hi
     * @return array
     */
    public function getSummaryTotal($margin_lo, $margin_hi)
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
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            ( (weight >= :lo AND weight <= :hi AND quality IN ('S', 'E', 'U', 'R', 'O', 'P') ) OR quality IN ('M', 'V') ) AND 
            (damage1 = '' OR damage1 = '02') AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate,
            ':lo' => $margin_lo,
            ':hi' => $margin_hi
        ));
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
     * Marks stock as reported to LANUV that have a certain quality.
     *
     * @param array $quality
     * @return void
     */
    public function markAsReportedNoWeight(array $qualities)
    {
		$sql = <<<SQL
        UPDATE stock
        SET lanuvreported = 1
        WHERE 
            buyer = :buyer AND 
            quality IN ('M', 'V') AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            (damage1 = '' OR damage1 = '02') AND
            csb_id IS NOT NULL
SQL;
        return R::exec($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }

    /**
     * Marks stock as reported to LANUV that have a certain quality within the weight range.
     *
     * @param string $qualities
     * @param float $margin_lo
     * @param float $margin_hi
     * @return void
     */
    public function markAsReportedWeight(array $qualities, $margin_lo, $margin_hi)
    {
		$sql = <<<SQL
        UPDATE stock
        SET lanuvreported = 1 
        WHERE 
            buyer = :buyer AND 
            quality IN ('S', 'E', 'U', 'R', 'O', 'P') AND 
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            (weight >= :lo AND weight <= :hi) AND 
            (damage1 = '' OR damage1 = '02') AND
            csb_id IS NOT NULL
SQL;
        return R::exec($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate,
            ':lo' => $margin_lo,
            ':hi' => $margin_hi
        ));
    }
    
    /**
     * Export the lanuv bean as csv.
     */
    public function exportAsCsv()
    {
        $stocks = $this->bean->getStock();
        require_once '../app/lib/parsecsv.lib.php';
        $csv = new parseCSV();
        $csv->output('lanuv.csv', $stocks, ',');
    }
    
    /**
     * Generates a CSV file after the specs of LANUV weekly price report.
     *
     * @throws Exception if the csv file could not be generated
     * @return bool wether the file could be created or not
     */
    public function exportAsLANUVReport()
    {
        foreach ( $this->bean->with(' ORDER BY id ')->ownLanuvitem as $id => $item ) {
            if ( $item->piggery == 0 ) continue; //skip when no piggies are in da house :-)
            $class = $item->quality;
            $total = $item->piggery;
            $weight = round( $item->sumweight, 0 );
            $price = round( round( $item->avgpricelanuv * 100, 2, PHP_ROUND_HALF_UP ), 0 );
            $mfa = round( round( $item->avgmfa * 10, 1, PHP_ROUND_HALF_UP ), 0 );
            error_log("{$class};{$total};{$weight};{$price};{$mfa}\n");
        }
        return true;
    }
    
    /**
     * Generate a CSV file after LANUV requirements and try to email it to this
     * beans company LANUV email address.
     *
     * @throws Exception if email could not be send
     * @return bool wether email was send or not
     */
    public function mail()
    {
        $this->bean->exportAsLANUVReport();
        //throw new Exception('Blubb');
        return true;
    }
    
    /**
     * Returns an array of all stock within the lanuv time periode.
     *
     * @return array
     */
    public function getStock()
    {
		$sql = <<<SQL
        SELECT
            stock.pubdate,
            stock.name AS sname,
            stock.earmark,
            stock.billnumber,
            person.nickname,
            person.account,
            person.name,
            stock.quality,
            stock.mfa,
            stock.weight,
            stock.damage1,
            stock.damage2,
            stock.dprice,
            stock.totaldprice,
            'Abzug',
            stock.bonusitem,
            stock.bonusweight,
            stock.totallanuvprice,
            stock.qs,
            'Pauschal',
            stock.lanuvreported
        FROM
            stock
        LEFT JOIN
            person ON person.id = stock.person_id
        WHERE
            stock.buyer = :buyer AND
            (stock.pubdate >= :startdate AND stock.pubdate <= :enddate) AND
            stock.csb_id IS NOT NULL
        ORDER BY
            stock.pubdate,
            stock.supplier,
            stock.name
SQL;
        return R::getAll($sql, array(
            ':buyer' => $this->bean->company->buyer,
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
        $this->bean->dirty = false;
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
