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
     * Holds the damage codes (Schadencodes) of stock to pick up in a summary.
     *
     * @var array
     */
    protected $damages = array(
        '01', '02', '03', '04', '05', '06', '07', '08', '09', '10'
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
		LEFT JOIN company ON company.id = analysis.company_id
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
     * Generate report for this analysis bean.
     *
     * If the grand total of stock beans is zero an Exception is thrown.
     *
     * @todo Refactor code to work recursive
     *
     * @return void
     */
    public function generateReport()
    {
        $this->bean->ownAnalysisitem = array();
        //$this->bean->person = null;
        $summary = $this->getSummaryTotal();
        if ($summary['piggery'] == 0) {
            throw new Exception('Grand total piggery is zero.');
        }
        $this->copyFromSummary(null, $this->bean, $summary, $summary['piggery']);
        $summary = $this->getSummaryDamageTotal();
        $this->copyFromSummaryDamage(null, $this->bean, $summary, $summary['piggery']);
        $qualities = array_merge($this->qualities, $this->nonQualities);
        foreach ($qualities as $quality) {
            $summary = $this->getSummary($quality); // totals and averages of the stock
            $analysisitem = R::dispense('analysisitem');
            $this->copyFromSummary($quality, $analysisitem, $summary, $this->bean->piggery);
            $this->bean->ownAnalysisitem[] = $analysisitem;
        }
        foreach ($this->damages as $damage) {
            $summary = $this->getSummaryDamage($damage); // totals and averages of the stock
            $analysisitem = R::dispense('analysisitem');
            $this->copyFromSummaryDamage($damage, $analysisitem, $summary, $this->bean->damagepiggery);
            $this->bean->ownAnalysisitem[] = $analysisitem;
        }

        // now we go through each supplier in the given time period and do the same
        $suppliers = $this->getSuppliers();
        $this->bean->ownAnalysis = array();
        foreach ($suppliers as $id => $supplier) {
            $person = R::findOne('person', " nickname = :supplier LIMIT 1 ", array(
                ':supplier' => $supplier['supplier']
            ));
            $subAnalysis = R::dispense('analysis');
            $subAnalysis->person = $person;
            $supplierSummary = $summary = $this->getSummaryTotalSupplier($person->nickname);
            $this->copyFromSummary(null, $subAnalysis, $summary, $summary['piggery']);
            $summary = $this->getSummaryDamageTotalSupplier($person->nickname);
            $this->copyFromSummaryDamage(null, $subAnalysis, $summary, $summary['piggery']);
            foreach ($qualities as $quality) {
                $summary = $this->getSummarySupplier($quality, $person->nickname);
                $subAnalysisitem = R::dispense('analysisitem');
                $this->copyFromSummary($quality, $subAnalysisitem, $summary, $subAnalysis->piggery);
                $subAnalysis->ownAnalysisitem[] = $subAnalysisitem;
            }
            foreach ($this->damages as $damage) {
                $summary = $this->getSummaryDamageSupplier($damage, $person->nickname);
                $subAnalysisitem = R::dispense('analysisitem');
                $this->copyFromSummaryDamage($damage, $subAnalysisitem, $summary, $subAnalysis->damagepiggery);
                $subAnalysis->ownAnalysisitem[] = $subAnalysisitem;
            }
            // if supplier has conditions that apply to invoices we add them here
            /*
            $conditions = $person->withCondition(' doesnotaffectinvoice = 0 ')->ownCondition;
            //$conditions = $supplier->ownAppliedcondition;
            foreach ($conditions AS $id => $condition) {
                $subAnalysisitem = R::dispense('analysisitem');
                $subAnalysisitem->kind = 2;
                $subAnalysisitem->quality = $condition->content . ' ' . I18n::__('condition_label_' . $condition->label);
                switch ($condition->label) {
                    case 'stockperitem':
                        $subAnalysisitem->piggery = $supplierSummary['piggery'];
                        $subAnalysisitem->sumtotaldprice = $subAnalysisitem->piggery * $condition->value;
                        $subAnalysisitem->avgprice = $condition->value;
                        break;

                    case 'stockperweight':
                        $subAnalysisitem->sumweight = $supplierSummary['sumweight'];
                        $subAnalysisitem->sumtotaldprice = $subAnalysisitem->sumweight * $condition->value;
                        $subAnalysisitem->avgprice = $condition->value;
                        break;

                    default:
                        // dunno?! nothing.
                        break;
                }
                $subAnalysis->ownAnalysisitem[] = $subAnalysisitem;
            }
            */
            $this->bean->ownAnalysis[] = $subAnalysis;
        }
        return true;
    }

    /**
     * Copies values from summary array into the given bean.
     *
     * @param string $quality or empty
     * @param $bean
     * @param array $summary
     * @param int $total
     * @return void
     */
    public function copyFromSummary($quality = '', $bean = null, array $summary = array(), $total = 0)
    {
        $bean->kind = 0; //this is a quality entry
        $bean->quality = $quality;
        $bean->piggery = $summary['piggery'];
        $bean->itwpiggery = $summary['sumitw'];
        if ($total != 0) {
            $bean->piggerypercentage = $summary['piggery'] * 100 / $total;
        } else {
            $bean->piggerypercentage = 0;
        }

        $bean->sumweight = $summary['sumweight'];
        $bean->sumtotaldprice = $summary['sumtotaldprice'];
        $bean->sumtotallanuvprice = $summary['sumtotallanuvprice'];
        $bean->sumtotalpricenet = $summary['sumtotalpricenet'];
        $bean->sumtotalpricenetitw = $summary['sumtotalpricenetitw'];
        $bean->avgmfa = $summary['avgmfa'];
        $bean->avgprice = $summary['avgprice'];
        $bean->avgpricenet = $summary['avgpricenet'];
        $bean->avgpricenetitw = $summary['avgpricenetitw'];
        $bean->avgpricelanuv = $summary['avgpricelanuv'];
        $bean->avgweight = $summary['avgweight'];
        $bean->avgdprice = $summary['avgdprice'];
        return true;
    }

    /**
     * Copies values from summary array into the given bean for damages.
     *
     * @param string $damage or empty
     * @param $bean
     * @param array $summary
     * @param int $total
     * @return void
     */
    public function copyFromSummaryDamage($damage = '', $bean = null, array $summary = array(), $total = 0)
    {
        $bean->kind = 1; //this is a damage entry
        $bean->damage = $damage;
        $bean->damagepiggery = $summary['piggery'];
        $bean->itwdamagepiggery = $summary['sumitw'];
        if ($total != 0) {
            $bean->damagepiggerypercentage = $summary['piggery'] * 100 / $total;
        } else {
            $bean->damagepiggerypercentage = 0;
        }
        $bean->damagesumweight = $summary['sumweight'];
        $bean->damagesumtotaldprice = $summary['sumtotaldprice'];
        $bean->damagesumtotallanuvprice = $summary['sumtotallanuvprice'];
        $bean->damagesumtotalpricenetitw = $summary['sumtotalpricenetitw'];
        $bean->damageavgmfa = $summary['avgmfa'];
        $bean->damageavgprice = $summary['avgprice'];
        $bean->damageavgpricenet = $summary['avgpricenet'];
        $bean->damageavgpricenetitw = $summary['avgpricenetitw'];
        $bean->damageavgpricelanuv = $summary['avgpricelanuv'];
        $bean->damageavgweight = $summary['avgweight'];
        $bean->damageavgdprice = $summary['avgdprice'];
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
     * Returns an array with information about a certain stock damage.
     *
     * @param string $damage
     * @return array
     */
    public function getSummaryDamage($damage)
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
            damage1 = :damage AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':buyer' => $this->bean->company->buyer,
            ':damage' => $damage,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }

    /**
     * Returns an array with information about a certain stock quality and supplier.
     *
     * @param string $quality
     * @param string $supplier
     * @return array
     */
    public function getSummarySupplier($quality, $supplier)
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
            supplier = :supplier AND
            buyer = :buyer AND
            quality = :quality AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':supplier' => $supplier,
            ':buyer' => $this->bean->company->buyer,
            ':quality' => $quality,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }

    /**
     * Returns an array with information about a certain stock damage and supplier.
     *
     * @param string $damage
     * @param string $supplier
     * @return array
     */
    public function getSummaryDamageSupplier($damage, $supplier)
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
            supplier = :supplier AND
            buyer = :buyer AND
            damage1 = :damage AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':supplier' => $supplier,
            ':buyer' => $this->bean->company->buyer,
            ':damage' => $damage,
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
     * Returns an array with information about a certain time period.
     *
     * @return array
     */
    public function getSummaryDamageTotal()
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
            damage1 != '' AND
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
     * Returns an array with information about a certain time period and a certain supplier.
     *
     * @param string $supplier
     * @return array
     */
    public function getSummaryTotalSupplier($supplier)
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
            supplier = :supplier AND
            buyer = :buyer AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':supplier' => $supplier,
            ':buyer' => $this->bean->company->buyer,
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ));
    }

    /**
     * Returns an array with information about a certain time period and a certain supplier.
     *
     * @param string $supplier
     * @return array
     */
    public function getSummaryDamageTotalSupplier($supplier)
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
            supplier = :supplier AND
            buyer = :buyer AND
            damage1 != '' AND
            (pubdate >= :startdate AND pubdate <= :enddate) AND
            csb_id IS NOT NULL
SQL;
        return R::getRow($sql, array(
            ':supplier' => $supplier,
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
        $this->bean->dirty = false;
        $this->bean->itwpiggery = 0;
        $this->bean->itwdamagepiggery = 0;
        $this->bean->startdate = date('Y-m-d');
        $this->bean->enddate = date('Y-m-d');
        $this->addConverter('startdate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('enddate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('piggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('itwpiggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('piggerypercentage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotaldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotallanuvprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotalpricenet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotalpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgmfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricenet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricelanuv', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgdprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagepiggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagepiggerypercentage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotaldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotallanuvprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damagesumtotalpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgmfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricenet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricelanuv', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgdprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('damageavgpricenetitw', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotalpricenetitw', array(
            new Converter_Decimal()
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
