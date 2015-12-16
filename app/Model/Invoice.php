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
 * Invoice model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Invoice extends Model
{    
    /**
     * Constructor.
     *
     * Set actions for list views.
     */
    public function __construct()
    {
        $this->setAction('index', array('idle', 'togglePaid'));
    }
    
    /**
     * Toggle the paid attribute and store the bean.
     *
     * @return void
     */
    public function togglePaid()
    {
        $this->bean->paid = ! $this->bean->paid;
        R::store($this->bean);
    }
        
    /**
     * Returns the vat bean.
     *
     * @return RedBean_OODBBean
     */
    public function vat()
    {
        if ( ! $this->bean->vat ) {
            $this->bean->vat = R::dispense('vat');
        }
        return $this->bean->vat;
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
                'name' => 'fy',
                'sort' => array(
                    'name' => 'invoice.fy'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
                )
            ),
            array(
                'name' => 'name',
                'sort' => array(
                    'name' => 'invoice.name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'bookingdate',
                'sort' => array(
                    'name' => 'invoice.bookingdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
            array(
                'name' => 'person_account',
                'sort' => array(
                    'name' => 'person.account'
                ),
                'callback' => array(
                    'name' => 'getPersonAccount'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'person_nickname',
                'sort' => array(
                    'name' => 'person.nickname'
                ),
                'callback' => array(
                    'name' => 'getPersonNickname'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'person_id',
                'sort' => array(
                    'name' => 'person.name'
                ),
                'callback' => array(
                    'name' => 'getPersonName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'dateofslaughter',
                'sort' => array(
                    'name' => 'invoice.dateofslaughter'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
            array(
                'name' => 'totalgros',
                'sort' => array(
                    'name' => 'invoice.totalgros'
                ),
                'class' => 'number',
                'filter' => array(
                    'tag' => 'number'
                ),
                'callback' => array(
                    'name' => 'getTotalgrosFormatted'
                )
            ),
            array(
                'name' => 'paid',
                'sort' => array(
                    'name' => 'invoice.paid'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                )
            )
        );
    }

    /**
     * Returns the totalgros of this bean nicely formatted.
     *
     * @return string
     */
    public function getTotalgrosFormatted()
    {
        return $this->bean->decimal('totalgros', 2);
    }

    /**
     * Returns the nickname of this beans person aka deliverer.
     *
     * @return string
     */
    public function getPersonNickname()
    {
        return $this->bean->person->nickname;
    }

    /**
     * Returns the account of this beans person aka deliverer.
     *
     * @return string
     */
    public function getPersonAccount()
    {
        return $this->bean->person->account;
    }
    
    /**
     * Returns the name of this beans person aka deliverer.
     *
     * @return string
     */
    public function getPersonName()
    {
        return $this->bean->person->name;
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
    }
    
    /**
     * Update.
     */
    public function update()
    {
        parent::update();
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
		LEFT JOIN person ON person.id = invoice.person_id
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
}
