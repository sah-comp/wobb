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
        if ($summary['piggery'] == 0) {
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
     * @param $bean
     * @param array $summary
     * @param int $total
     * @return void
     */
    public function copyFromSummary($quality = '', $bean, array $summary = array(), $total)
    {
        //$bean->kind = 0; //this is a quality entry
        $bean->quality = $quality;
        $bean->piggery = $summary['piggery'];
        if ($total != 0) {
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
     * Returns the string "sent" when the report was sent successfully to LANUV by email.
     */
    public function wasSent()
    {
        if ($this->bean->sent) {
            return "sent";
        }
        return '';
    }

    /**
     * Return a string representing the status of the lanuv bean.
     * The bean could be dirty, that is some re-calculation changed the data and
     * the lanuv bean has to be recalculated.
     * The lanuv bean may not yet have been sent by e-mail to LANUV which is mandatory
     * from May 2020 on.
     */
    public function getStatus()
    {
        $status = [];
        if ($this->bean->dirty) {
            $status[] = I18n::__('lanuv_isdirty');
        }
        if (!$this->bean->sent) {
            $status[] = I18n::__('lanuv_not_yet_sent');
        }
        return implode(', ', $status);
    }

    /**
     * Export the calendar week of slaughtered stock as csv.
     */
    public function exportWeekAsCsv()
    {
        $stock = $this->bean->getStock();
        $filename = I18n::__('lanuv_weekascsv_filename', null, array($this->bean->weekOfYear()));
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
            I18n::__('lanuv_fn_pubdate'), //Schlachtdatum
            I18n::__('lanuv_fn_name'), //Schlachtnummer
            I18n::__('lanuv_fn_name_ext'), //Schlachtnummer gemäß elektronischen Protokoll
            I18n::__('lanuv_fn_earmark'), //Täto
            I18n::__('lanuv_fn_billnumber'), //Abrechnungsnummer
            I18n::__('lanuv_fn_person_nickname'), //Lieferantencode
            I18n::__('lanuv_fn_person_account'), //Lieferantennummer
            I18n::__('lanuv_fn_person_name'), //Lieferantenname
            I18n::__('lanuv_fn_quality'), //Handelsklasse
            I18n::__('lanuv_fn_mfa'), //MFA %
            I18n::__('lanuv_fn_weight'), //Nettoschlachtgewicht in KG
            I18n::__('lanuv_fn_damage1'), //Schaden 1
            I18n::__('lanuv_fn_damage2'), //Schaden 2 Organverwurf
            I18n::__('lanuv_fn_dprice'), //Preis je KG ohne Boni Euro
            I18n::__('lanuv_fn_totaldprice'), //Warenwert ohne Boni Euro
            I18n::__('lanuv_fn_discount1'), //Abzug Schäden in KG zu Schaden 1
            I18n::__('lanuv_fn_discount2'), //Abzug Schäden in KG zu Schaden 2
            I18n::__('lanuv_fn_discount3'), //Abzug Schäden in KG zu Schaden 3 Leberschaden
            I18n::__('lanuv_fn_cost'),
            I18n::__('lanuv_fn_bonus_item'),
            I18n::__('lanuv_fn_bonus_weight'),
            I18n::__('lanuv_fn_bonus_value'),
            I18n::__('lanuv_fn_totallanuvprice'),
            I18n::__('lanuv_fn_lanuvprice'),
            I18n::__('lanuv_fn_qs'),
            I18n::__('lanuv_fn_flat'),
            I18n::__('lanuv_fn_lanuvreported'),
            I18n::__('lanuv_fn_paidslaughter')
        ];
        $csv->heading = true;
        $csv->data = $stock;
        $csv->output($filename);
    }

    /**
     * Returns a csv object with the lanuv weekly statistics.
     *
     * @return parseCSV
     */
    public function exportAsCsv()
    {
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'ISO-8859-1');
        $csv->delimiter = ";";
        $csv->output_delimiter = ";";
        $csv->linefeed = "\r\n";
        $csv->titles = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'
        ];
        $csv->heading = false;
        $csv->data = $this->bean->generateLanuvStatsAsCsv();
        return $csv;
    }

    /**
     * Returns an array with lanuvitem beans as array to be used with csv parse lib.
     *
     * @see https://meldfleisch.ble.de/docs/Datensatz-HB-3.2.pdf
     *
     * @return array
     */
    public function generateLanuvStatsAsCsv()
    {
        $data = [];
        foreach ($this->bean->with(' ORDER BY id ')->ownLanuvitem as $id => $item) {
            if ($item->piggery == 0) {
                continue;
            } //skip when no piggies are in da house :-)
            $line = [
                'A' => '20',
                'B' => date('d.m.Y', strtotime($this->bean->startdate)),
                'C' => $this->bean->company->county,
                'D' => $this->bean->company->region,
                'E' => $this->bean->company->subpart,
                'F' => 'Schweine',
                'G' => 'SW',
                'H' => $item->quality,
                'I' => '',
                'J' => '1',
                'K' => '1',
                'L' => $item->piggery,
                'M' => round($item->sumweight, 0),
                'N' => round(round($item->avgpricelanuv * 100, 2, PHP_ROUND_HALF_UP), 0),
                'O' => round(round($item->avgmfa * 10, 1, PHP_ROUND_HALF_UP), 0)
            ];
            error_log(implode(";", $line));
            $data[] = $line;
        }
        return $data;
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
            '-' AS jd1,
            stock.earmark,
            stock.billnumber,
            person.nickname,
            person.account,
            REPLACE(person.name, "\r\n", " "),
            stock.quality,
            FORMAT(stock.mfa, 1, 'de_DE') AS mfa,
            FORMAT(stock.weight, 1, 'de_DE') AS weight,
            stock.damage1,
            stock.damage2,
            FORMAT(stock.dprice, 3, 'de_DE') AS dprice,
            FORMAT(stock.totaldprice, 2, 'de_DE') AS totaldprice,
            '-' AS jd2,
            '-' AS jd3,
            IF(LOCATE('L', stock.damage2), 'Ja', 'Nein') AS liver,
            FORMAT(stock.cost, 2, 'de_DE') AS cost,
            FORMAT(stock.bonusitem, 2, 'de_DE') AS bonusitem,
            FORMAT(stock.bonusweight, 2, 'de_DE') AS bonusweight,
            FORMAT(stock.bonus, 2, 'de_DE') AS bonusvalue,
            FORMAT(stock.totallanuvprice, 3, 'de_DE') AS totallanuvprice,
            FORMAT(ROUND(stock.totallanuvprice / stock.weight, 3), 3, 'de_DE') AS dpricelanuv,
            stock.qs,
            '-' AS jd4,
            stock.lanuvreported,
            '-' AS jd5
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
            new Converter_Mysqldate()
        ));
        $this->addConverter('enddate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('piggery', array(
            new Converter_Decimal()
        ));
        $this->addConverter('piggerypercentage', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotallanuvprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgmfa', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgweight', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgpricelanuv', array(
            new Converter_Decimal()
        ));
        $this->addConverter('sumtotaldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('avgdprice', array(
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
