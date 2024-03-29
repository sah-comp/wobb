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
class Model_Csb extends Model
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
     * Returns wether the csb was already calculated or not.
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
     * Returns wether the csb was already billed or not.
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
     * Returns the string "sent" when the report was sent successfully to iQAgrar by email.
     */
    public function wasIqagrarSent()
    {
        if ($this->bean->iqagrarsent) {
            return "sent";
        }
        return '';
    }

    /**
     * Mark all lanuv beans as dirty which are effected by this csb bean.
     *
     * @return RedBean_OODBBean for chaining
     */
    public function markInvolvedLanuvAsDirty()
    {
        R::exec(" UPDATE analysis SET dirty = 1 WHERE (startdate <= :pubdate AND enddate >= :pubdate ) AND analysis_id IS NULL and person_id IS NULL ", array(':pubdate' => $this->bean->pubdate));
        return $this->bean;
    }

    /**
     * Mark all analysis beans as dirty which are effected by this csb bean.
     *
     * @return RedBean_OODBBean for chaining
     */
    public function markInvolvedAnalysisAsDirty()
    {
        R::exec(" UPDATE lanuv SET dirty = 1 WHERE (startdate <= :pubdate AND enddate >= :pubdate ) ", array(':pubdate' => $this->bean->pubdate));
        return $this->bean;
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
        //return strftime("%A, %e. %B %Y <span class=\"week\">Woche %V</span>", strtotime($this->bean->pubdate));
        return date("d.m.Y", strtotime($this->bean->pubdate));
    }

    /**
     * Returns wether iQAgrar needs to be sent or not.
     *
     * @return bool
     */
    public function hasIqagrar()
    {
        return $this->bean->company->hasiqagrar;
    }

    /**
     * Generate the data to be sent to iQ-Agrar using ADS format.
     *
     * @return string
     */
    public function generateADS()
    {
        $ts = time();
        $file = '';

        // ADIS header
        $file .= "DH990001000000000800090000208000900003080009000040600090000624000900009080" . "\r\n";
        $header = [
            'VH990001',
            str_pad("DD:", 8, " ", STR_PAD_RIGHT),
            str_pad("1996", 8, " ", STR_PAD_RIGHT),
            date('Ymd', $ts),
            date('hms', $ts),
            str_pad($this->bean->company->name, 24, " ", STR_PAD_RIGHT),
            str_pad('AGRO2017', 8, " ", STR_PAD_RIGHT)
        ];
        $file .= implode($header) . "\r\n";

        // ADIS data stock
        $file .= "DN61010100610301140006103140800061031015000610308140006103150520061031603100610319031006103180310061032002000610014150" . "\r\n";

        // cycle through all our piggies
        $stocks = R::find("stock", " csb_id = :csb_id ORDER BY earmark, mfa DESC", ['csb_id' => $this->bean->getId()]);
        foreach ($stocks as $id => $stock) {
            $data = [
                'VN610101',
                str_pad($this->bean->company->ident, 14, " ", STR_PAD_LEFT),
                str_replace("-", "", $stock->pubdate),
                str_pad($stock->name, 15, " ", STR_PAD_LEFT),
                str_pad($stock->earmark, 14, " ", STR_PAD_LEFT),
                str_pad($stock->weight * 100, 5, "0", STR_PAD_LEFT),
                str_pad($stock->mfa * 10, 3, "0", STR_PAD_LEFT),
                str_pad($stock->speck * 10, 3, "0", STR_PAD_LEFT),
                str_pad($stock->flesh * 10, 3, "0", STR_PAD_LEFT),
                str_pad($stock->quality, 2, " ", STR_PAD_LEFT),
                str_pad($stock->vvvo, 15, " ", STR_PAD_LEFT)
            ];
            $file .= implode($data) . "\r\n";
        }

        // ADIS data damages
        $file .= "DN6101050061030114000610314080006103101500061030904000610539500" . "\r\n";

        // cycle through all our damage1 piggies
        $stocks = R::find("stock", " csb_id = :csb_id AND damage1 != '' ORDER BY earmark, mfa DESC", ['csb_id' => $this->bean->getId()]);
        foreach ($stocks as $id => $stock) {
            $damage = R::findOne("var", " kind = 'damage1' AND name = :code LIMIT 1 ", [':code' => $stock->damage1]);
            $data = [
                'VN610105',
                str_pad($this->bean->company->ident, 14, " ", STR_PAD_LEFT),
                str_replace("-", "", $stock->pubdate),
                str_pad($stock->name, 15, " ", STR_PAD_LEFT),
                str_pad($stock->damage1, 4, " ", STR_PAD_LEFT),
                str_pad($damage->note, 50, " ", STR_PAD_LEFT)
            ];
            $file .= implode($data) . "\r\n";
        }

        // cycle through all our damage12piggies
        $stocks = R::find("stock", " csb_id = :csb_id AND damage2 != '' ORDER BY earmark, mfa DESC", ['csb_id' => $this->bean->getId()]);
        foreach ($stocks as $id => $stock) {
            $damage = R::findOne("var", " kind = 'damage2' AND name = :code LIMIT 1 ", [':code' => $stock->damage2]);
            $data = [
                'VN610105',
                str_pad($this->bean->company->ident, 14, " ", STR_PAD_LEFT),
                str_replace("-", "", $stock->pubdate),
                str_pad($stock->name, 15, " ", STR_PAD_LEFT),
                str_pad('9999', 4, " ", STR_PAD_LEFT), // use of 9999 because ADIS allows only numeric data
                str_pad($damage->note, 50, " ", STR_PAD_LEFT)
            ];
            $file .= implode($data) . "\r\n";
        }

        $file .= "ZN" . "\r\n";
        return $file;
    }

    /**
     * Returns the count of a certain damage code within this days stock.
     *
     * @param string The damage code
     * @return bool
     */
    public function hasDamageCode($code)
    {
        return $this->bean
                    ->withCondition(' ( damage1 = :code OR damage2 = :code ) ', array(
                        ':code' => $code
                    ))
                    ->countOwn('stock');
    }

    /**
     * Returns count of stock beans which need the users attention.
     *
     * @return int
     */
    public function hasStockThatNeedsAttention()
    {
        return $this->bean
                    ->withCondition(" damage1 IN (?) ORDER BY supplier, name", array(
                        '06'
                    ))
                    ->countOwn('stock');
    }

    /**
     * Returns the plan bean desc(ription) attribute if there is any.
     *
     * @return string
     */
    public function hasPlanningInformation()
    {
        if ($plan = R::findOne('plan', " pubdate = :slaughterdate LIMIT 1", [':slaughterdate' => $this->bean->pubdate])) {
            return $plan->desc;
        }
        return '';
    }

    /**
     * Returns an array with stock that needs manual work.
     *
     * These are frontmost those stock beans which have damage1 equal to '06' as the code
     * for being vorlaeufig.
     *
     * @return array
     */
    public function getStockThatNeedsAttention()
    {
        return R::find('stock', " csb_id = ? AND damage1 IN (?) ORDER BY name", array($this->bean->getId(), DAMAGE_CODE_A_UNSUITABLE));
    }

    /**
     * Returns the stock beans which have a certain code.
     *
     * @param string The damage code
     * @return bool
     */
    public function getStockWithDamage($code)
    {
        //R::debug(true);
        return $this->bean
                    ->withCondition(' ( damage1 = :code OR damage2 = :code ) ORDER by supplier ', array(
                        ':code' => $code
                    ))
                    ->ownStock;
    }

    /**
     * Returns the latest csb bean.
     *
     * @return $csb
     */
    public function getLatest()
    {
        if (! $latest = R::findOne('csb', " ORDER BY pubdate DESC LIMIT 1 ")) {
            $latest = R::dispense('csb');
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
        return I18n::__('purchase_h1_' . $label);
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
                    'name' => 'csb.pubdate'
                ),
                'callback' => array(
                    'name' => 'localizedDate'
                ),
                'filter' => array(
                    'tag' => 'date'
                )
            ),
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
                'name' => 'csbformat_id',
                'sort' => array(
                    'name' => 'csbformat.name'
                ),
                'callback' => array(
                    'name' => 'getCsbformatName'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'sanename',
                'sort' => array(
                    'name' => 'csb.sanename'
                ),
                'filter' => array(
                    'tag' => 'text'
                )
            ),
            array(
                'name' => 'piggery',
                'sort' => array(
                    'name' => 'csb.piggery'
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
		LEFT JOIN company ON company.id = csb.company_id
		LEFT JOIN csbformat ON csbformat.id = csb.csbformat_id
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
     * Returns the name of this beans csbformat.
     *
     * @return string
     */
    public function getCsbformatName()
    {
        return $this->bean->csbformat->name;
    }

    /**
     * Returns a the given string safely to use as filename or url.
     *
     * @link http://stackoverflow.com/questions/2668854/sanitizing-strings-to-make-them-url-and-filename-safe
     *
     * What it does:
     * - Replace all weird characters with dashes
     * - Only allow one dash separator at a time (and make string lowercase)
     *
     * @param string $string the string to clean
     * @param bool $is_filename false will allow additional filename characters
     * @return string
     */
    public function sanitizeFilename($string = '', $is_filename = false)
    {
        $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);
        return mb_strtolower(preg_replace('/--+/u', '-', $string));
    }

    /**
     * dispense a new csb bean.
     */
    public function dispense()
    {
        $this->bean->extension = '';
        $this->bean->size = 0;
        $this->bean->mime = '';
        $this->bean->file = '';
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
        $this->addConverter('nextweekprice', array(
            new Converter_Decimal()
        ));
        $this->addValidator('pubdate', array(
            new Validator_HasValue()
        ));
        $this->addValidator('company_id', array(
            new Validator_HasValue()
        ));
        $this->addValidator('csbformat_id', array(
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
        //$files = reset(Flight::request()->files);
        //$file = reset($files);
        $filesArray = (array) Flight::request()->files;
        $file = reset($filesArray);
        $file = reset($file);
        //error_log('Type ' . gettype($file));
        //$file = (array) $file;
        if ($this->bean->getId() || (empty($file) || (isset($file['error']) && $file['error'] == 4))) {
            // do not handle the file a second time
        } else {
            if (isset($file['error']) && $file['error']) {
                $this->addError($file['error'], 'file');
                throw new Exception('fileupload error');
            }
            $json = json_encode($file);
            //error_log('Upload ' . $json);
            $file_parts = pathinfo($file['name']);
            $this->bean->sanename = $this->sanitizeFilename($file_parts['filename']);
            $this->bean->extension = strtolower($file_parts['extension']);
            $this->bean->file = $this->bean->sanename.'.'.$this->bean->extension;
            if (! move_uploaded_file($file['tmp_name'], Flight::get('upload_dir') . '/' . $this->bean->file)) {
                $this->addError('move_upload_file_failed', 'file');
                throw new Exception('move_upload_file_failed');
            }
            $this->size = filesize(Flight::get('upload_dir') . '/' . $this->bean->file);
            $this->mime = $file['type'];
        }
        if ($this->bean->company_id) {
            $this->bean->company = R::load('company', $this->bean->company_id);
        } else {
            unset($this->bean->company);
        }
        if ($this->bean->csbformat_id) {
            $this->bean->csbformat = R::load('csbformat', $this->bean->csbformat_id);
        } else {
            unset($this->bean->csbformat);
        }
        parent::update();
    }

    /**
     * Dispatches the import depending on the choosen import method and source.
     *
     * @return mixed
     */
    public function importStock():mixed
    {
        if (! $this->bean->csbformat) {
            throw new Exception('No import method selected');
        }
        return $this->{$this->bean->csbformat->method}(); //call the method defined in the choosen bean
    }

    /**
     * Read the data from a csv that derived from an Excel file.
     *
     * This method was build quick and dirty to fix the missing .csb import file from
     * slaughter day 2023-08-07 when the server broke down.
     */
    public function importFromCsvExcel($value = '')
    {
        $this->bean->piggery = 0;
        $this->bean->ownStock = [];

        $file = Flight::get('upload_dir') . '/' . $this->bean->file;
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->heading = false;
        $csv->parse($file);
        foreach ($csv->data as $key => $row) {
            /*
            foreach ($row as $i => $value) {
                error_log($i . ': ' . $value);
            }
            */
            //error_log('Käufer ' . $row[1]);
            if ($row[1] != $this->bean->company->buyer) {
                continue; // not the required buyer code
            }
            //error_log('Values ' . implode(';', $row));
            $stock = R::dispense('stock');
            $stock->buyer = $this->bean->company->buyer;
            $stock->pubdate = $this->bean->pubdate;
            $stock->name = (int)$row[0];
            $stock->earmark = strtoupper($row[2]);
            $stock->supplier = trim(substr($stock->earmark, 0, 2));
            $stock->quality = $row[9];
            $stock->weight = $this->bean->csbformat->makeFloatFromCSBFloat($row[7]);
            $stock->mfa = $this->bean->csbformat->makeFloatFromCSBFloat($row[8]);
            $stock->qs = true;
            $stock->vvvo = $row[3];
            if (trim($row[10])) {
                $stock->damage1 = sprintf('%02d', trim($row[10]));
            }

            if (strtolower($stock->quality) == 'z') {
                $stock->mfa = 0;
            }

            // check for initiative tierwohl | check for itw
            if ($this->bean->company->hastierwohl) {
                if (substr($stock->earmark, -strlen($this->bean->company->tierwohlflag)) === $this->bean->company->tierwohlflag) {
                    $stock->itw = true; // this stock is qualified to be paid additional amount ITW
                    $stock->earmark = substr($stock->earmark, 0, strlen($stock->earmark)-1);
                    if ($stockman = R::findOne('stockman', "earmark = ? AND vvvo = ? AND tierwohlnetperstock <> 0 LIMIT 1", [$stock->earmark, $stock->vvvo])) {
                        // there is a special price defined for this sub deliverer
                        $stock->tierwohlnetperstock = $stockman->tierwohlnetperstock;
                    } else {
                        // no special price, use the usual price
                        $stock->tierwohlnetperstock = $this->bean->company->tierwohlnetperstock;
                    }
                }
            }

            $stock->lanuvreported = 0;
            $stock->billnumber = 0;
            $stock->person = $stock->getPersonBySupplier();
            if (!$stock->person->getId()) {
                throw new Exception_UnknownSupplier($stock->supplier);
            }

            $this->bean->ownStock[] = $stock;
            $this->bean->piggery++;
        }
        return true;
    }

    /**
     * Read the data from a csv that Matthäus Classification Software produces.
     *
     * @since 2024-01-04
     */
    public function importFromCsvM($value = '')
    {
        $this->bean->piggery = 0;
        $this->bean->ownStock = [];

        $file = Flight::get('upload_dir') . '/' . $this->bean->file;
        $csv = new \ParseCsv\Csv();
        $csv->encoding('UTF-8', 'UTF-8');
        $csv->delimiter = ";";
        $csv->heading = false;
        $csv->parse($file);
        foreach ($csv->data as $key => $row) {
            if ($row[2] != $this->bean->company->buyer) {
                continue; // not the required buyer code
            }
            //error_log('Values ' . implode(';', $row));
            $stock = R::dispense('stock');
            $stock->buyer = $this->bean->company->buyer;

            $stock->pubdate = date_create_from_format('d.m.Y', $row[0])->format('Y-m-d');
            if ($stock->pubdate != $this->bean->pubdate) {
                throw new Exception_Csbfiledatemismatch('Date in file does not match your slaughterdate');
            }
            if (substr(strtoupper($row[4]), 0, 2) != strtoupper($row[3])) {
                throw new Exception_UnknownSupplier(substr(strtoupper($row[4]), 0, 2) . ' ' . strtoupper($row[3]));
            }

            
            $stock->damage1 = ''; //set to empty string
            $stock->damage2 = ''; //set to empty string, null causes trouble
            $stock->name = (int)$row[1];
            $stock->earmark = strtoupper($row[4]);
            $stock->supplier = strtoupper($row[3]);
            $stock->quality = strtoupper($row[6]);
            $stock->weight = $row[10];
            $stock->mfa = $row[7];
            $stock->flesh = $row[8];
            $stock->speck = $row[9];
            $stock->tare = $row[11];
            $stock->qs = true;
            $stock->vvvo = $row[5];
            if (trim($row[12])) {
                $stock->damage1 = strtoupper(trim($row[12]));
            }

            // check for liver damages
            if (trim($row[13])) {
                $befund = strtoupper(trim($row[13]));
                //error_log($befund);
                if (strpos($befund, DAMAGE_CODE_B_LIVER_GT5) !== false) {
                    $stock->damage2 = 'L';
                } else {
                    $stock->damage2 = '';
                }
            }

            if (strtolower($stock->quality) == 'z') {
                $stock->mfa = 0;
            }

            // check for initiative tierwohl | check for itw
            if ($this->bean->company->hastierwohl) {
                if (substr($stock->earmark, -strlen($this->bean->company->tierwohlflag)) === $this->bean->company->tierwohlflag) {
                    $stock->itw = true; // this stock is qualified to be paid additional amount ITW
                    $stock->earmark = substr($stock->earmark, 0, strlen($stock->earmark)-1);
                    if ($stockman = R::findOne('stockman', "earmark = ? AND vvvo = ? AND tierwohlnetperstock <> 0 LIMIT 1", [$stock->earmark, $stock->vvvo])) {
                        // there is a special price defined for this sub deliverer
                        $stock->tierwohlnetperstock = $stockman->tierwohlnetperstock;
                    } else {
                        // no special price, use the usual price
                        $stock->tierwohlnetperstock = $this->bean->company->tierwohlnetperstock;
                    }
                }
            }

            $stock->lanuvreported = 0;
            $stock->billnumber = 0;
            $stock->person = $stock->getPersonBySupplier();
            if (!$stock->person->getId()) {
                throw new Exception_UnknownSupplier($stock->supplier);
            }

            $this->bean->ownStock[] = $stock;
            $this->bean->piggery++;
        }
        return true;
    }

    /**
     * Reads the file and tries to import stock from the given file.
     *
     * @todo Implement a test on imported. Already imported CSB file have to be rejected
     * @todo Check deliverer VVVO number for Tierwohl qualification instead of checking via flag
     */
    public function importFromCsb()
    {
        $file = Flight::get('upload_dir') . '/' . $this->bean->file;
        if (! $fh = fopen($file, "r")) {
            return false;
        }
        $this->bean->piggery = 0;
        $this->bean->ownStock = array();
        while (! feof($fh)) {
            $line = fgets($fh, 4096);
            if ($this->bean->csbformat->getBuyerFromCSB($line) != $this->bean->company->buyer) {
                continue;
            }
            $stock = R::dispense('stock');
            $stock->import($this->bean->csbformat->exportFromCSB($this->bean->company, $line));

            if (strtolower($stock->quality) == 'z') {
                $stock->mfa = 0;
            }

            if ($stock->pubdate != $this->bean->pubdate) {
                throw new Exception_Csbfiledatemismatch('Date in CSB file does not match your csb date');
            }

            // check for initiative tierwohl | check for itw
            if ($this->bean->company->hastierwohl) {
                if (substr($stock->earmark, -strlen($this->bean->company->tierwohlflag)) === $this->bean->company->tierwohlflag) {
                    $stock->itw = true; // this stock is qualified to be paid additional amount ITW
                    $stock->earmark = substr($stock->earmark, 0, strlen($stock->earmark)-1);
                    if ($stockman = R::findOne('stockman', "earmark = ? AND vvvo = ? AND tierwohlnetperstock <> 0 LIMIT 1", [$stock->earmark, $stock->vvvo])) {
                        // there is a special price defined for this sub deliverer
                        $stock->tierwohlnetperstock = $stockman->tierwohlnetperstock;
                    } else {
                        // no special price, use the usual price
                        $stock->tierwohlnetperstock = $this->bean->company->tierwohlnetperstock;
                    }
                }
            }

            $stock->lanuvreported = 0;
            $stock->billnumber = 0;
            $stock->person = $stock->getPersonBySupplier();
            if (!$stock->person->getId()) {
                throw new Exception_UnknownSupplier($stock->supplier);
            }

            $this->bean->ownStock[] = $stock;
            $this->bean->piggery++;
        }
        fclose($fh);
        Flight::get('user')->notify(I18n::__('csb_already_imported', null, array($this->bean->piggery)));
        return true;
    }

    /**
     * Looks up possible aliasses for stock beans and transfers them to their new owner.
     *
     * For example stock beans with earmark 'VO8603' have to be transferred to 'VX8603' as
     * they do not belong to 'Vollmer Viehhandel' but to 'Vollmer Landwirt' which runs under
     * VX instead of VO. As it happened to be forgotten often to do this manually therefore
     * we developed this function for convenience.
     *
     * @return bool either true when aliasses where checked and transffered or false if not
     */
    public function checkAliasses()
    {
        $aliasses = R::findAll('kidnap', " ORDER BY earmark DESC, vvvo DESC");
        if (! $aliasses) {
            return false;
        }
        $sql = "UPDATE stock SET earmark = :new_earmark, person_id = :new_pid, supplier = :new_supplier WHERE (vvvo = :vvvo AND earmark = :earmark) AND csb_id = :csb_id";
        foreach ($aliasses as $id => $alias) {
            $new_earmark = strtoupper($alias->person->nickname . substr($alias->earmark, 2));
            R::exec($sql, array(
                ':new_earmark' => $new_earmark,
                ':new_pid' => $alias->person->getId(),
                ':new_supplier' => strtoupper($alias->person->nickname),
                ':earmark' => $alias->earmark,
                ':vvvo' => $alias->vvvo,
                ':csb_id' => $this->bean->getId()
            ));
        }
        return true;
    }

    /**
     * Create deliverer and their subdeliverer beans.
     *
     * When there is no person matching a main deliverer a new person bean will be created.
     * The baseprice will be added with rel*price values of the found person bean. A subdeliverer
     * will not have a *price because they will inherit from their each and every main deliverer bean.
     *
     * @return bool
     */
    public function makeDeliverer()
    {
        $sqlqsd = "SELECT count(id) AS totalqs FROM stock WHERE csb_id = :csb_id AND supplier = :supplier AND qs = 1";
        $sqlqss = "SELECT count(id) AS totalqs FROM stock WHERE csb_id = :csb_id AND earmark = :earmark AND qs = 1";

        $sqlitwd = "SELECT count(id) AS totalitw FROM stock WHERE csb_id = :csb_id AND supplier = :supplier AND itw = 1";
        $sqlitws = "SELECT count(id) AS totalitw FROM stock WHERE csb_id = :csb_id AND earmark = :earmark AND itw = 1";

        $stocks = R::getAll("SELECT count(id) AS total, supplier FROM stock WHERE csb_id = :csb_id GROUP BY supplier", array(':csb_id' => $this->bean->getId()));

        $nonqs = []; // container for eventually non QS earmarks.

        foreach ($stocks as $id => $stock) {
            // Deliverer owns one or more earmarks of an csb day
            $deliverer = R::dispense('deliverer');
            if (! $deliverer->person = R::findOne('person', ' nickname = ? LIMIT 1', array($stock['supplier']))) {
                $p = R::dispense('person');
                $p->nickname = $stock['supplier'];
                $deliverer->person = $p;
            }
            $deliverer->supplier = $stock['supplier'];
            $deliverer->earmark = '';
            $deliverer->piggery = $stock['total'];
            if ($deliverer->person->nextweekprice && $this->bean->nextweekprice) {
                $deliverer->dprice = $this->bean->nextweekprice + $deliverer->person->reldprice;
                $deliverer->sprice = $this->bean->nextweekprice + $deliverer->person->relsprice;
            } else {
                $deliverer->dprice = $this->bean->baseprice + $deliverer->person->reldprice;
                $deliverer->sprice = $this->bean->baseprice + $deliverer->person->relsprice;
            }
            // if person has set a fixed service price we use that. It will override previous price settings
            if ($deliverer->person->fixsprice) {
                $deliverer->sprice = $deliverer->person->fixsprice;
            }
            // if person has set a fixed dealer price we use that. It will override previous price settings
            if ($deliverer->person->fixdprice) {
                $deliverer->dprice = $deliverer->person->fixdprice;
            }
            $deliverer->qspiggery = R::getCell($sqlqsd, array(
                ':csb_id' => $this->bean->getId(),
                ':supplier' => $deliverer->supplier
            ));

            $deliverer->itwpiggery = R::getCell($sqlitwd, array(
                ':csb_id' => $this->bean->getId(),
                ':supplier' => $deliverer->supplier
            ));

            // Subdeliverer is owned by deliverer bean
            $substocks = R::getAll("SELECT count(id) AS total, earmark, supplier, vvvo FROM stock WHERE csb_id = :csb_id AND supplier = :supplier GROUP BY earmark", array(':csb_id' => $this->bean->getId(), ':supplier' => $stock['supplier']));
            foreach ($substocks as $_sub_id => $substock) {
                $subdeliverer = R::dispense('deliverer');
                $subdeliverer->person = $deliverer->person;
                $subdeliverer->supplier = $substock['supplier'];
                $subdeliverer->earmark = $substock['earmark'];
                $subdeliverer->vvvo = $substock['vvvo'];
                $subdeliverer->piggery = $substock['total'];
                $subdeliverer->dprice = 0;
                $subdeliverer->sprice = 0;
                $subdeliverer->qspiggery = R::getCell($sqlqss, array(
                    ':csb_id' => $this->bean->getId(),
                    ':earmark' => $subdeliverer->earmark
                ));

                $subdeliverer->itwpiggery = R::getCell($sqlitws, array(
                    ':csb_id' => $this->bean->getId(),
                    ':earmark' => $subdeliverer->earmark
                ));

                $deliverer->ownDeliverer[] = $subdeliverer;
                // Check if this earmark is non QS
                if ($deliverer->isEarmarkNonQS($subdeliverer->earmark) > 0) {
                    $nonqs[] = $subdeliverer->earmark;
                }
            }
            $this->bean->ownDeliverer[] = $deliverer;
        }
        if (count($nonqs) > 0) {
            //there are earmarks which are non QS in this batch. Add a notification
            $nonqs_flat = implode(", ", $nonqs); // flatten the earmarks nicely
            Flight::get('user')->notify(I18n::__('csb_has_nonqs', null, array($nonqs_flat)), 'error');
        }
        return true;
    }

    /**
     * Some data integrity checks.
     *
     * Q1: Are there no L damage2 for this day? Something might be wrong. How to handle the case that there
     * really is no liver damage on that day?
     *
     * Q2: Is there stock weighing less than 10 kilograms?
     *
     * What else could be checked?
     *
     * @return bool
     */
    public function checkData($value = '')
    {
        // Q1
        $countDamage1 = R::getCell('SELECT count(*) AS livers FROM stock WHERE damage2 = ? AND csb_id = ?', [
            'L',
            $this->bean->getId()
        ]);
        if ($countDamage1 == 0) {
            Flight::get('user')->notify(I18n::__('csb_has_no_liverdamages'), 'warning');
        } else {
            if ($this->bean->piggery != 0) {
                $percentage = $countDamage1 * 100 / $this->bean->piggery;
                Flight::get('user')->notify(I18n::__('csb_has_liverdamages', null, [$percentage, $countDamage1, $this->bean->piggery]), 'info');
            }
        }

        // Q2
        $countLowWeight = R::getCell('SELECT count(*) as lowweight FROM stock WHERE weight <= ? and csb_id = ?', [
            10,
            $this->bean->getId()
        ]);
        if ($countLowWeight > 0) {
            Flight::get('user')->notify(I18n::__('csb_has_very_lowweight_stock'), 'warning');
        }
        return true;
    }

    /**
     * Checks the slaughter day data against the planned data of the slaughter day.
     *
     * If there is no plan with the date given simply return, else check and compare things
     * like piggery, itwpiggery and so on.
     *
     * @return void
     */
    public function checkPlan(): void
    {
        if (!$plan = R::findOne('plan', " pubdate = ?", [$this->bean->pubdate])) {
            Flight::get('user')->notify(I18n::__('csb_no_plan_found'), 'warning');
            return;
        }
        //error_log('CSB ' . $this->bean->piggery . ' == PLAN ' . $plan->piggery);
        $results = [];
        //Flight::get('user')->notify(I18n::__('csb_compared_to_plan_result'));
        if ($this->bean->baseprice != $plan->baseprice) {
            $results[] = I18n::__('csb_plan_baseprice_differs');
        }
        if ($this->bean->nextweekprice && $this->bean->nextweekprice != $plan->nextweekprice) {
            $results[] = I18n::__('csb_plan_nextweekprice_differs');
        }
        if ($this->bean->piggery != $plan->piggery) {
            $results[] = I18n::__('csb_plan_piggery_differs');
        }
        $deliverers_plan = R::find('deliverer', " plan_id = ? ORDER BY supplier", [$plan->getId()]);
        //$deliverers_csb = R::find('deliverer', " csb_id = ? ORDER BY supplier", [$this->bean->getId()]);
        foreach ($deliverers_plan as $id => $deliverer_plan) {
            if ($deliverer_csb = R::findOne('deliverer', " csb_id = ? AND supplier = ?", [$this->bean->getId(), $deliverer_plan->supplier])) {
                if ($deliverer_plan->piggery !== $deliverer_csb->piggery) {
                    $results[] = I18n::__('csb_plan_piggery_differs_deliverer', null, [$deliverer_csb->supplier]);
                }
                if ($deliverer_plan->itwpiggery !== $deliverer_csb->itwpiggery) {
                    $results[] = I18n::__('csb_plan_itwpiggery_differs_deliverer', null, [$deliverer_csb->supplier]);
                }
            } else {
                // there is an unplanned deliverer, probably due to alias splitting of a main deliverer
            }
        }
        if (count($results)) {
            $result = implode(', ', $results);
            Flight::get('user')->notify(I18n::__('csb_compared_to_plan_result_errors', null, [$result]), 'error');
        } else {
            Flight::get('user')->notify(I18n::__('csb_compared_to_plan_result_okay'));
        }
        return;
    }

    /**
     * Checks the QS database for QS and TW qualification.
     *
     * @todo get rid of MAGIC numer 2001 (Schweinemast production type)
     * @throws new Exception_NonQS when a deliverer is not QS certified
     * @return mixed
     */
    public function checkQSITW(): mixed
    {
        if (!$this->bean->company->hastierwohl) {
            return null;
        }

        if (!$this->bean->company->wsdl) {
            return null;
        }
        // clear all stock from itw
        $sql = "UPDATE stock SET itw = 0, tierwohlnetperstock = 0 WHERE csb_id = :csb_id";
        R::exec($sql, [
            ':csb_id' => $this->bean->getId()
        ]);
        ini_set("default_socket_timeout", 60);
        $client = new SoapClient($this->bean->company->wsdl);
        foreach ($this->bean->ownDeliverer as $id => $deliverer) {
            $deliverer->itwpiggery = 0; //reset itw counter
            foreach ($deliverer->ownDeliverer as $sub_id => $sub) {
                $sub->itwpiggery = 0; //reset iwt counter
                try {
                    //error_log($sub->vvvo . " auf ITW/QS prüfen … ");
                    $response = $client->selectQSTW([
                        'locationId' => $sub->vvvo,
                        'btartId' => '2001'
                    ]);
                    if (isset($response->certifications)) {
                        //error_log(" response->certifications->qsCertification is " . $response->certifications->qsCertification);
                        if ($response->certifications->qsCertification != 1) {
                            throw new Exception_NonQS($sub->vvvo);
                        }
                        if ($response->certifications->twCertification) {
                            // TW certified, add up as itwpiggery
                            $sub->itw = true;
                            $sub->itwpiggery = $sub->piggery;
                            $deliverer->itwpiggery += $sub->piggery;
                            // which tw bonus?
                            $twbonus = $this->bean->company->tierwohlnetperstock;
                            if ($stockman = R::findOne('stockman', "earmark = ? AND vvvo = ? AND tierwohlnetperstock <> 0 LIMIT 1", [$sub->earmark, $sub->vvvo])) {
                                // there is a special price defined for this sub deliverer
                                $twbonus = $stockman->tierwohlnetperstock;
                            }
                            // update all stock of the TW certified deliverer to be ITW
                            $sql = "UPDATE stock SET itw = 1, tierwohlnetperstock = :twbonus WHERE earmark = :earmark AND csb_id = :csb_id";
                            R::exec($sql, [
                            ':earmark' => $sub->earmark,
                            ':csb_id' => $this->bean->getId(),
                            ':twbonus' => $twbonus
                            ]);
                        } else {
                            // This subdeliverer is NOT TW certified
                            $sub->itw = false;
                        }
                    } else {
                        // at least non QS, which disqualifies the badge from purchasing
                        throw new Exception_NonQS($sub->vvvo);
                        //error_log(" … ist ohne ITW/QS");
                        //$sub->itw = false;
                    }
                } catch (Exception_NonQS $e) {
                    //throw new Exception_NonQS($sub->vvvo);
                    Flight::get('user')->notify(I18n::__('qs_check_deliverer_notqs', null, [$sub->earmark, $sub->vvvo]), 'warning');
                } catch (Exception $e) {
                    error_log('Check VVVO ' . $sub->vvvo . ' failed with ' . $e);
                    throw new Exception_ITWUnreachable($sub->vvvo);
                }
            }
        }
        return true;
    }

    /**
     * Calculates prices for each stock of all enabled deliveres of this csb bean.
     *
     * For each deliverer and it subdeliverers sums and averages get calculated.
     *
     * @return void
     */
    public function calculation()
    {
        foreach ($this->bean
                  ->withCondition(" enabled = 1 ORDER BY supplier ")
                  ->ownDeliverer as $_id => $deliverer) {
            $deliverer->totalnet = 0;
            $deliverer->totalnetitw = 0;
            $deliverer->totalnetsprice = 0;
            $deliverer->subtotalnet = 0;
            $deliverer->vatvalue = 0;
            $deliverer->totalgros = 0;
            $deliverer->totalnetlanuv = 0;
            $deliverer->totalweight = 0;
            $deliverer->totalmfa = 0;
            $deliverer->hasmfacount = 0;
            $deliverer->meanweight = 0;
            $deliverer->meanmfa = 0;
            $deliverer->meandprice = 0;
            $deliverer->meansprice = 0;
            foreach ($deliverer->with(" ORDER BY earmark ")->ownDeliverer as $_sub_id => $subdeliverer) {
                $subdeliverer->setBaseprices($this->bean);
                $summary = $subdeliverer->calculation($this->bean);
                // save some of the summary to the subdeliverer
                $subdeliverer->totalnet = $summary['totalnet'];
                $subdeliverer->totalnetitw = $summary['totalnetitw'];
                $subdeliverer->totalnetsprice = $summary['totalnetsprice'];
                $subdeliverer->totalweight = $summary['totalweight'];
                // subdeliverer mean values
                if ($summary['piggery'] != 0) {
                    $subdeliverer->meanweight = $summary['totalweight'] / $summary['piggery'];
                }
                if ($summary['hasmfacount'] != 0) {
                    $subdeliverer->meanmfa = $summary['totalmfa'] / $summary['hasmfacount'];
                }
                if ($summary['totalweight'] != 0) {
                    $subdeliverer->meandprice = $summary['totalnet'] / $summary['totalweight'];
                    $subdeliverer->meansprice = $summary['totalnetsprice'] / $summary['totalweight'];
                    $subdeliverer->meandpricelanuv = $summary['totalnetlanuv'] / $summary['totalweight'];
                }
                // add all up
                $deliverer->totalnet += $summary['totalnet'];
                $deliverer->totalnetitw += $summary['totalnetitw'];
                $deliverer->totalnetsprice += $summary['totalnetsprice'];
                $deliverer->totalnetlanuv += $summary['totalnetlanuv'];
                $deliverer->totalweight += $summary['totalweight'];
                $deliverer->totalmfa += $summary['totalmfa'];
                $deliverer->hasmfacount += $summary['hasmfacount'];
            }
            // calculate means
            if ($deliverer->piggery != 0) {
                $deliverer->meanweight = $deliverer->totalweight / $deliverer->piggery;
            }
            if ($deliverer->hasmfacount != 0) {
                $deliverer->meanmfa = $deliverer->totalmfa / $deliverer->hasmfacount;
            }
            if ($deliverer->totalweight != 0) {
                $deliverer->meandprice = $deliverer->totalnet / $deliverer->totalweight;
                $deliverer->meansprice = $deliverer->totalnetsprice / $deliverer->totalweight;
                $deliverer->meandpricelanuv = $deliverer->totalnetlanuv / $deliverer->totalweight;
            }
            $deliverer->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated a subdeliverer
            $deliverer->calcVat();
            $deliverer->applyConditions($this->bean);
        }
        $this->bean->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated the csb bean
        $this->calcAverages();
        return null;
    }

    /**
     * Sets the average prices for this slaughter day.
     *
     * @uses Model_Analysis::getTotalSummary() to calculate this companies average dealer price
     * @uses Model_Lanuv::getTotalSummary() to calculate this days LANUV average price
     * @return void
     */
    public function calcAverages()
    {
        // get the average of the dealer price
        $analysis = R::dispense('analysis');
        $analysis->company = $this->bean->company;
        $analysis->startdate = $this->bean->pubdate;
        $analysis->enddate = $this->bean->pubdate;
        $summary = $analysis->getSummaryTotal();
        $this->bean->companyprice = $summary['avgprice'];
        // do the same for average lanuv price
        $analysis = R::dispense('lanuv');
        $analysis->company = $this->bean->company;
        $analysis->startdate = $this->bean->pubdate;
        $analysis->enddate = $this->bean->pubdate;
        $summary = $analysis->getSummaryTotal(Model_Lanuv::LOWER_MARGIN, Model_Lanuv::UPPER_MARGIN);
        $this->bean->lanuvprice = $summary['avgpricelanuv'];
        return null;
    }

    /**
     * Generates bills for all enabled supplier beans of this csb bean.
     *
     * @return void
     */
    public function billing()
    {
        foreach ($this->bean
                  ->withCondition(" enabled = 1 ORDER BY supplier ")
                  ->ownDeliverer as $id => $deliverer) {
            $deliverer->billing($this->bean);
        }
        $this->bean->billingdate = date('Y-m-d H:i:s'); //stamp that we have billed the csb bean
        return null;
    }

    /**
     * after_delete.
     *
     * After the bean was deleted from the database, we will also delete the real file.
     *
     */
    public function after_delete()
    {
        if (is_file(Flight::get('upload_dir').'/'.$this->bean->file)) {
            unlink(Flight::get('upload_dir').'/'.$this->bean->file);
        }
    }
}
