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
 * Csb model.
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
     * Returns wether the plan was already calculated or not.
     *
     * @return bool
     */
    public function wasCalculated()
    {
        if ($this->bean->calcdate === null || $this->bean->calcdate == '0000-00-00 00:00:00') {
            return false;
        }
        return true;
    }

    /**
     * Returns wether the plan was already billed or not.
     *
     * @return bool
     */
    public function wasBilled()
    {
        if ($this->bean->billingdate === null || $this->bean->billingdate == '0000-00-00 00:00:00') {
            return false;
        }
        return true;
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
     * Returns the latest csb bean.
     *
     * @return RedBean_OODBBean $csb
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
     * dispense a new csb bean.
     */
    public function dispense()
    {
        $this->bean->piggery = 0;
        $this->bean->calcdate = null;
        $this->bean->pubdate = date('Y-m-d');
        $this->addConverter(
            'pubdate',
            new Converter_Mysqldate()
        );
        $this->addConverter(
            'calcdate',
            new Converter_Mysqldatetime()
        );
        $this->addConverter('baseprice', array(
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
     * update.
     *
     * This will check for file uploads.
     */
    public function update()
    {
        parent::update();
    }
	
	/**
	 * Returns an array of deliverers ordered by person and invoice.
	 *
	 * @return array
	 */
	public function getDeliverers()
	{
		return $this->bean->with(' ORDER BY person_id ')->ownDeliverer;
	}
}
