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
     * Holds the invoice kinds.
     *
     * 0 means a voucher, 1 is a delayed voucher.
     *
     * @var array
     */
    public $kinds = array(
        0,
        1
    );

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
     * Returns the invoice kinds.
     *
     * @return array
     */
    public function getKinds()
    {
        return $this->kinds;
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
                    'tag' => 'number'
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
                    'name' => 'getPersonNameWeird'
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
     * Cancel the invoice.
     *
     * @throws Exception if no billing number can be generated
     * @return RedBean_OODBean the new bean that now holds the canceled one
     */
    public function cancelation()
    {
        $canceled = R::dup($this->bean);
        $canceled->totalnet = $canceled->totalnet * -1;
        $canceled->subtotalnet = $canceled->subtotalnet * -1;
        $canceled->vatvalue = $canceled->vatvalue * -1;
        $canceled->totalgros = $canceled->totalgros * -1;
        $canceled->bonusnet = $canceled->bonusnet * -1;
        $canceled->costnet = $canceled->costnet * -1;
        $canceled->totalnetnormal = $canceled->totalnetnormal * -1;
        $canceled->totalnetfarmer = $canceled->totalnetfarmer * -1;
        $canceled->totalnetother = $canceled->totalnetother * -1;
        $canceled->canceled = true;
        if ( ! $nextbillingnumber = $canceled->company->nextBillingnumber() ) {
            throw new Exception();
        }
        $canceled->name = $nextbillingnumber;
        R::store($canceled);
        return $canceled;
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
     * @param int $maxlength
     * @return string
     */
    public function getPersonName($maxlength = NULL)
    {
        if ( $maxlength && mb_strlen($this->bean->person->name) > $maxlength ) {
            return mb_substr($this->bean->person->name, 0, $maxlength).'...';
        }
        return $this->bean->person->name;
    }
    
    /**
     * Returns the name of this beans person, aka deliverer.
     *
     * @todo Find out why getPersonName() above gives an error on 5.3.29
     * @return string
     */
    public function getPersonNameWeird()
    {
        return $this->bean->person->name;
    }
    
    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addConverter('bookingdate', array(
            new Converter_MysqlDate()
        ));
        $this->addConverter('dateofslaughter', array(
            new Converter_MysqlDate()
        ));
        $this->addConverter('duedate', array(
            new Converter_MysqlDate()
        ));
        $this->addConverter('totalnet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('subtotalnet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('vatvalue', array(
            new Converter_Decimal()
        ));
        $this->addConverter('totalgros', array(
            new Converter_Decimal()
        ));
        $this->addConverter('bonusnet', array(
            new Converter_Decimal()
        ));
        $this->addConverter('costnet', array(
            new Converter_Decimal()
        ));
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
