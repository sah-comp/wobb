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
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Lanuv extends Model
{
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
     * Generate report for this lanuv bean.
     *
     * @return void
     */
    public function generateReport()
    {
        $this->bean->ownLanuvitem = array();
        // Qualities with weight margins
        foreach ($this->qualities as $quality) {
            $summary = $this->getSummaryQuality($quality, 80.0, 110.0); // totals and averages of the stock
            $lanuvitem = R::dispense('lanuvitem');
            $lanuvitem->quality = $quality;
            $lanuvitem->piggery = $summary['piggery'];
            $lanuvitem->sumweight = $summary['sumweight'];
            $lanuvitem->sumtotaldprice = $summary['sumtotaldprice'];
            $lanuvitem->avgmfa = $summary['avgmfa'];
            $lanuvitem->avgprice = $summary['avgprice'];
            $lanuvitem->avgweight = $summary['avgweight'];
            $lanuvitem->avgdprice = $summary['avgdprice'];
            $this->bean->ownLanuvitem[] = $lanuvitem;
        }
        // Non-Qualities without weight margins
        foreach ($this->nonQualities as $quality) {
            $summary = $this->getSummaryNonQuality($quality); // totals and averages of the stock
            $lanuvitem = R::dispense('lanuvitem');
            $lanuvitem->quality = $quality;
            $lanuvitem->piggery = $summary['piggery'];
            $lanuvitem->sumweight = $summary['sumweight'];
            $lanuvitem->sumtotaldprice = $summary['sumtotaldprice'];
            $lanuvitem->avgmfa = $summary['avgmfa'];
            $lanuvitem->avgprice = $summary['avgprice'];
            $lanuvitem->avgweight = $summary['avgweight'];
            $lanuvitem->avgdprice = $summary['avgdprice'];
            $this->bean->ownLanuvitem[] = $lanuvitem;
        }
        return true;
    }
    
    /**
     * Returns an array with information about a certain stock quality.
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
            (sum(totaldprice) / sum(weight)) as avgprice,
            avg(weight) as avgweight,
            avg(dprice) as avgdprice
        FROM stock 
        WHERE 
            buyer = :buyer AND 
            quality = :quality AND 
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            (weight >= :lo AND weight <= :hi) AND 
            damage1 = '';
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
            (sum(totaldprice) / sum(weight)) as avgprice,
            avg(weight) as avgweight,
            avg(dprice) as avgdprice
        FROM stock 
        WHERE 
            buyer = :buyer AND 
            quality = :quality AND 
            (pubdate >= :startdate AND pubdate <= :enddate) AND 
            damage1 = '';
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
