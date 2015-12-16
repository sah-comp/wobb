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
     * Holds the qualities (Handelsklasse) of stock to pick up in a summary.
     *
     * @var array
     */
    protected $qualities = array(
        'S', 'E', 'U', 'R', 'O', 'P', 'Z'
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
    public function generateReport()
    {
        $this->bean->ownAnalysisitem = array();
        $summary = $this->getSummaryTotal();
        $this->copyFromSummary(null, $this->bean, $summary, $summary['piggery']);
        $qualities = array_merge( $this->qualities, $this->nonQualities );
        foreach ($qualities as $quality) {
            $summary = $this->getSummary($quality); // totals and averages of the stock
            $analysisitem = R::dispense('analysisitem');
            $this->copyFromSummary($quality, $analysisitem, $summary, $this->bean->piggery);
            $this->bean->ownAnalysisitem[] = $analysisitem;
        }
        $suppliers = $this->getSuppliers();
        foreach ($suppliers as $id => $supplier) {
            error_log('Analysis for ' . $supplier['supplier']);
        }
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
        $bean->quality = $quality;
        $bean->piggery = $summary['piggery'];
        $bean->piggerypercentage = $summary['piggery'] * 100 / $total;
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
     * Returns an array with information about a certain stock quality.
     *
     * @param string $quality
     * @return array
     */
    public function getSummary($quality)
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
     * Returns an array with information about a certain time period.
     *
     * @return array
     */
    public function getSummaryTotal()
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
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }
    
    /**
     * Returns an array with all suppliers of the given time period.
     *
     * @return array
     */
    public function getSuppliers()
    {
        $sql = <<<SQL
        SELECT
            supplier
        FROM stock
        WHERE
            buyer = :buyer AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
        GROUP BY
            supplier
        ORDER BY
            supplier
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
