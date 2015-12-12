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
        return ( $this->bean->calcdate != '0000-00-00 00:00:00');
    }
    
    /**
     * Returns wether the csb was already billed or not.
     *
     * @return bool
     */
    public function wasBilled()
    {
        return ( $this->bean->billingdate != '0000-00-00 00:00:00');
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
        return strftime( "%A, %e. %B %Y Woche %V", strtotime( $this->bean->pubdate ) );
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
     * Returns an array with stock that needs manual work.
     *
     * These are frontmost those stock beans which have damage1 equal to '06' as the code
     * for being vorlaeufig.
     *
     * @return array
     */
    public function getStockThatNeedsAttention()
    {
        return R::find('stock', " csb_id = ? AND damage1 IN (?) ORDER BY supplier, name", array($this->bean->getId(), "06"));
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
     * @return RedBean_OODBBean $csb
     */
    public function getLatest()
    {
        if ( ! $latest = R::findOne('csb', " ORDER BY pubdate DESC LIMIT 1 ")) {
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
        /*
        return I18n::__('purchase_h1_mask', null, 
            array(
                $this->localizedDate('pubdate'),
                $this->bean->company->name,
                $this->decimal('baseprice', 3)
            )
        );
        */
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
        $this->bean->piggery = 0;
        $this->bean->calcdate = null;
        $this->bean->pubdate = date('Y-m-d');
        $this->addConverter('pubdate',
            new Converter_Mysqldate()
        );
        $this->addConverter('calcdate',
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
        $files = reset(Flight::request()->files);
        $file = reset($files);
        if ($this->bean->getId() || (empty($file) || $file['error'] == 4)) {
            // do not handle the file a second time
        }
        else
        {
            if ($file['error']) {
                $this->addError($file['error'], 'file');
                throw new Exception('fileupload error');
            }
            $file_parts = pathinfo($file['name']);
            $this->bean->sanename = $this->sanitizeFilename($file_parts['filename']);
            $this->bean->extension = strtolower($file_parts['extension']);
            $this->bean->file = $this->bean->sanename.'.'.$this->bean->extension;
            if ( ! move_uploaded_file($file['tmp_name'], Flight::get('upload_dir') . '/' . $this->bean->file)) {
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
     * Reads the file and tries to import stock from the given file.
     *
     * @todo Implement a test on imported. Already imported CSB file have to be rejected
     */
    public function importFromCsb()
    {
        $file = Flight::get('upload_dir') . '/' . $this->bean->file;
        if ( ! $fh = fopen($file, "r")) return false;
        $this->bean->piggery = 0;
        $this->bean->ownStock = array();
        while ( ! feof($fh) ) {
            $line = fgets($fh, 4096);
            if ($this->bean->csbformat->getBuyerFromCSB($line) != $this->bean->company->buyer) continue;
            $stock = R::dispense('stock');
            $stock->setValidationMode(Model::VALIDATION_MODE_IMPLICIT);
            $stock->import($this->bean->csbformat->exportFromCSB($this->bean->company, $line));
            
            $stock->lanuvreported = 0;
            $stock->billnumber = 0;
            $stock->person = $stock->getPersonBySupplier();
            
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
        $aliasses = R::findAll('kidnap');
        if ( ! $aliasses ) return false;
        $sql = "UPDATE stock SET earmark = :new_earmark, person_id = :new_pid, supplier = :new_supplier WHERE earmark = :earmark AND csb_id = :csb_id";
        foreach ($aliasses as $id => $alias) {
            $new_earmark = strtoupper( $alias->person->nickname . substr( $alias->earmark, 2 ) );
            R::exec($sql, array(
                ':new_earmark' => $new_earmark,
                ':new_pid' => $alias->person->getId(),
                ':new_supplier' => strtoupper( $alias->person->nickname ),
                ':earmark' => $alias->earmark,
                ':csb_id' => $this->bean->getId()
            ));
            error_log('Transffered ' . $alias->earmark . ' to ' . $new_earmark);
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
     */
    public function makeDeliverer()
    {
        $sqlqsd = "SELECT count(id) AS totalqs FROM stock WHERE csb_id = :csb_id AND supplier = :supplier AND qs = 1";
        $sqlqss = "SELECT count(id) AS totalqs FROM stock WHERE csb_id = :csb_id AND earmark = :earmark AND qs = 1";
        $stocks = R::getAll("SELECT count(id) AS total, earmark, supplier FROM stock WHERE csb_id = :csb_id GROUP BY supplier", array(':csb_id' => $this->bean->getId()));
        foreach ($stocks as $id => $stock) {
            // Deliverer owns one or more earmarks of an csb day
            $deliverer = R::dispense('deliverer');
            if ( ! $deliverer->person = R::findOne('person', ' nickname = ? LIMIT 1', array($stock['supplier']))) {
                $p = R::dispense('person');
                $p->nickname = $stock['supplier'];
                $deliverer->person = $p;
            }
            $deliverer->supplier = $stock['supplier'];
            $deliverer->earmark = '';
            $deliverer->piggery = $stock['total'];
            $deliverer->dprice = $this->bean->baseprice + $deliverer->person->reldprice;
            $deliverer->sprice = $this->bean->baseprice + $deliverer->person->relsprice;
            $deliverer->qspiggery = R::getCell($sqlqsd, array(
                ':csb_id' => $this->bean->getId(),
                ':supplier' => $deliverer->supplier
            ));
            // Subdeliverer is owned by deliverer bean
            $substocks = R::getAll("SELECT count(id) AS total, earmark, supplier FROM stock WHERE csb_id = :csb_id AND supplier = :supplier GROUP BY earmark", array(':csb_id' => $this->bean->getId(), ':supplier' => $stock['supplier']));
            foreach ($substocks as $_sub_id => $substock) {
                $subdeliverer = R::dispense('deliverer');
                $subdeliverer->person = $deliverer->person;
                $subdeliverer->supplier = $substock['supplier'];
                $subdeliverer->earmark = $substock['earmark'];
                $subdeliverer->piggery = $substock['total'];
                $subdeliverer->dprice = 0;
                $subdeliverer->sprice = 0;
                $subdeliverer->qspiggery = R::getCell($sqlqss, array(
                    ':csb_id' => $this->bean->getId(),
                    ':earmark' => $subdeliverer->earmark
                ));
                $deliverer->ownDeliverer[] = $subdeliverer;
            }
            $this->bean->ownDeliverer[] = $deliverer;
        }
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
            $deliverer->totalnetsprice = 0;
            $deliverer->subtotalnet = 0;
            $deliverer->vatvalue = 0;
            $deliverer->totalgros = 0;
            $deliverer->totalcost = 0;
            $deliverer->totalnetlanuv = 0;
            $deliverer->totalweight = 0;
            $deliverer->totalmfa = 0;
            $deliverer->hasmfacount = 0;
            $deliverer->meanweight = 0;
            $deliverer->meanmfa = 0;
            $deliverer->meandprice = 0;
            $deliverer->meansprice = 0;
            foreach ($deliverer->with(" ORDER BY earmark ")->ownDeliverer as $_sub_id => $subdeliverer) {
                if ( ! $subdeliverer->dprice ) $subdeliverer->dprice = $deliverer->dprice;
                if ( ! $subdeliverer->sprice ) $subdeliverer->sprice = $deliverer->sprice;
                $summary = $subdeliverer->calculation($this->bean);
                // save some of the summary to the subdeliverer
                $subdeliverer->totalnet = $summary['totalnet'];
                $subdeliverer->totalnetsprice = $summary['totalnetsprice'];
                $subdeliverer->totalweight = $summary['totalweight'];
                // subdeliverer mean values
                if ( $summary['piggery'] != 0 ) {
                    $subdeliverer->meanweight = $summary['totalweight'] / $summary['piggery'];
                }
                if ( $summary['hasmfacount'] != 0 ) {
                    $subdeliverer->meanmfa = $summary['totalmfa'] / $summary['hasmfacount'];
                }
                if ( $summary['totalweight'] != 0 ) {
                    $subdeliverer->meandprice = $summary['totalnet'] / $summary['totalweight'];
                    $subdeliverer->meansprice = $summary['totalnetsprice'] / $summary['totalweight'];
                    $subdeliverer->meandpricelanuv = $summary['totalnetlanuv'] / $summary['totalweight'];
                }
                // add all up
                $deliverer->totalnet += $summary['totalnet'];
                $deliverer->totalnetsprice += $summary['totalnetsprice'];
                $deliverer->totalnetlanuv += $summary['totalnetlanuv'];
                $deliverer->totalweight += $summary['totalweight'];
                $deliverer->totalmfa += $summary['totalmfa'];
                $deliverer->hasmfacount += $summary['hasmfacount'];
            }
            // calculate means
            if ( $deliverer->piggery != 0 ) 
                $deliverer->meanweight = $deliverer->totalweight / $deliverer->piggery;
            if ( $deliverer->hasmfacount != 0 ) 
                $deliverer->meanmfa = $deliverer->totalmfa / $deliverer->hasmfacount;
            if ( $deliverer->totalweight != 0 ) {
                $deliverer->meandprice = $deliverer->totalnet / $deliverer->totalweight;
                $deliverer->meansprice = $deliverer->totalnetsprice / $deliverer->totalweight;
                $deliverer->meandpricelanuv = $deliverer->totalnetlanuv / $deliverer->totalweight;
            }
            $deliverer->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated a subdeliverer
            $deliverer->calcVat();
        }
        $this->bean->calcdate = date('Y-m-d H:i:s'); //stamp that we have calculated the csb bean
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
