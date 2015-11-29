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
 * Person model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Person extends Model
{
    /**
     * Constructor.
     *
     * Set actions for list views.
     */
    public function __construct()
    {
        $this->setAction('index', array('idle', 'toggleEnabled', 'expunge'));
    }
    
    /**
     * Returns an array with billingtransport names.
     *
     * This determines which way the bills are proccessed. They might be printed, send by
     * email or both ways.
     *
     * @return array
     */
    public function getBillingtransports()
    {
        return array(
            'email',
            'print',
            'both'
        );
    }
    
    /**
     * Toggle the enabled attribute and store the bean.
     *
     * @return void
     */
    public function toggleEnabled()
    {
        $this->bean->enabled = ! $this->bean->enabled;
        R::store($this->bean);
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
                'name' => 'nickname',
                'sort' => array(
                    'name' => 'person.nickname'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'account',
                'sort' => array(
                    'name' => 'person.account'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'organization',
                'sort' => array(
                    'name' => 'person.organization'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'email',
                'sort' => array(
                    'name' => 'person.email'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'phone',
                'sort' => array(
                    'name' => 'person.phone'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'enabled',
                'sort' => array(
                    'name' => 'person.enabled'
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
     * Returns an address bean of this person with a given label.
     *
     * @param string $label defaults to 'default'
     * @return RedBean_OODBBean $address
     */
    public function getAddress($label = 'default')
    {
        if ( ! $address = R::findOne('address', 'label = ? AND person_id = ?', array($label, $this->bean->getId())) ) {
            $address = R::dispense('address');
        }
        return $address;
    }

    /**
     * Returns keywords from this bean for tagging.
     *
     * @var array
     */
    public function keywords()
    {
        return array(
            $this->bean->email,
            $this->bean->phone,
            $this->bean->fax,
            $this->bean->account,
            $this->bean->vatid,
            $this->bean->firstname,
            $this->bean->lastname,
            $this->bean->organization,
            $this->bean->nickname,
            $this->bean->phoneticfirstname,
            $this->bean->phoneticlastname
        );
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->autoTag(true);
        $this->autoInfo(true);
        $this->addConverter('relsprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('reldprice', array(
            new Converter_Decimal()
        ));
        $this->addConverter('qsdiscount', array(
            new Converter_Decimal()
        ));
        $this->addValidator('nickname', array(
            new Validator_HasValue(),
            new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'nickname'))
        ));
    }

    /**
     * Update.
     *
     * @todo Implement a switch to decide wether to use first/last or last/first name order
     */
    public function update()
    {
        if ($this->bean->pricing_id) {
            $this->bean->pricing = R::load('pricing', $this->bean->pricing_id);
        } else {
            unset($this->bean->pricing);
        }
        
        if ($this->bean->vat_id) {
            $this->bean->vat = R::load('vat', $this->bean->vat_id);
        } else {
            unset($this->bean->vat);
        }
        /*
        if ($this->bean->email) {
            $this->addValidator('email', array(
                new Validator_IsEmail(),
                new Validator_IsUnique(array('bean' => $this->bean, 'attribute' => 'email'))
            ));
        }
        */
		// set the phonetic names
		$this->bean->phoneticlastname = soundex($this->bean->lastname);
		$this->bean->phoneticfirstname = soundex($this->bean->firstname);
		// set the name according to sort rule
		$this->bean->name = implode(' ', array($this->bean->firstname, $this->bean->lastname));
		// company name
		if (trim($this->bean->name) == '' && $this->bean->organization || $this->bean->company) {
			$this->bean->name = $this->bean->organization;
		}
		if (trim($this->bean->name) == '') {
			$this->bean->name = $this->bean->nickname;
		}
		parent::update();
    }
}
