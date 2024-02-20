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
 * Piggery model.
 *
 * A piggery model holds the numbers of stock slaughtered and other stock information.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Piggery extends Model
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
		LEFT JOIN company ON company.id = piggery.company_id
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
     * @return bool
     */
    public function generateReport()
    {
        $days = R::find('csb', " pubdate BETWEEN :startdate AND :enddate ORDER BY pubdate", [
            ':startdate' => $this->bean->startdate,
            ':enddate' => $this->bean->enddate
        ]);
        if (count($days) == 0) {
            throw new Exception('There are no records within this period');
        }

        $total = 0;
        $this->bean->ownPiggeryitem = [];
        foreach ($days as $id => $day) {
            $total += $day->piggery;
            $piggeryitem = R::dispense('piggeryitem');
            $piggeryitem->stockcount = $day->piggery;
            $piggeryitem->pubdate = $day->pubdate;
            $this->bean->ownPiggeryitem[] = $piggeryitem;
        }
        $this->bean->stockcount = $total;
        return true;
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
        $this->bean->startdate = date('Y-m-d');
        $this->bean->enddate = date('Y-m-d');
        $this->bean->dirty = false;
        $this->addConverter('startdate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('enddate', array(
            new Converter_Mysqldate()
        ));
        $this->addConverter('stockcount', array(
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
