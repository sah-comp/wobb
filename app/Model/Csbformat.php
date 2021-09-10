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
 * Csbformat model.
 *
 * A csbformat model describes how a CSB import stock entry is formed.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Csbformat extends Model
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
                'name' => 'name',
                'sort' => array(
                    'name' => 'name'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'active',
                'sort' => array(
                    'name' => 'csbformat.active'
                ),
                'callback' => array(
                    'name' => 'boolean'
                ),
                'filter' => array(
                    'tag' => 'bool'
                ),
                'width' => '5rem'
            )
        );
    }

    /**
     * Returns an array with stock information taken from a line of csb file.
     *
     * @todo Configurable Format
     *
     * @param $company
     * @param string $line from a CSB file
     * @return array
     */
    public function exportFromCSB($company, $line = '')
    {
        return array(
            'buyer' => $this->getBuyerFromCSB($line),
            'pubdate' => $this->makeDateFromCSBDate(substr($line, 3, 8)),
            'name' => (int)trim(substr($line, 12, 7)),
            'supplier' => trim(substr($line, 23, 2)),
            'earmark' => trim(substr($line, 23, 10)),
            'quality' => trim(substr($line, 33, 1)),
            'weight' => trim($this->makeFloatFromCSBFloat(substr($line, 55, 6))),
            'mfa' => trim($this->makeFloatFromCSBFloat(substr($line, 40, 4))),
            'flesh' => trim($this->makeFloatFromCSBFloat(substr($line, 45, 4))),
            'speck' => trim($this->makeFloatFromCSBFloat(substr($line, 50, 4))),
            'tare' => trim($this->makeFloatFromCSBFloat(substr($line, 62, 4))),
            'damage1' => trim(substr($line, 67, 2)),
            'damage2' => trim(substr($line, 88, 3)),
            'qs' => (trim(substr($line, 109, 1)) == 'Q') ? true : false,
            'vvvo' => trim(substr($line, 111, 15))
        );
    }

    /**
     * Return the buyer code from a line of csb file.
     *
     * @param string $line from a CSB file
     * @return string
     */
    public function getBuyerFromCSB($line = '')
    {
        return trim(substr($line, 20, 3));
    }

    /**
     * Returns a double from a german number.
     *
     * @return string
     */
    public function makeFloatFromCSBFloat($csb_double = '')
    {
        return (float)str_replace(',', '.', $csb_double);
    }

    /**
     * Returns a date with 4-digit year.
     *
     * @param string CSB date
     * @return string MySQL Date string
     */
    public function makeDateFromCSBDate($csb_date = '')
    {
        $fragments = explode('.', $csb_date);
        if (! is_array($fragments) || count($fragments) != 3) {
            return date('Y-m-d');
        }
        $day = $fragments[0];
        $month = $fragments[1];
        $year = $fragments[2];
        $dt = DateTime::createFromFormat('y', $year);
        $year = $dt->Format('Y');
        return date('Y-m-d', strtotime($year . '-' . $month . '-'. $day));
    }

    /**
     * Dispense.
     */
    public function dispense()
    {
        $this->addValidator('name', array(
            new Validator_HasValue()
        ));
    }
}
