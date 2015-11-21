<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html lang="<?php echo $language ?>" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html lang="<?php echo $language ?>" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html lang="<?php echo $language ?>" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="<?php echo $language ?>" class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title><?php echo $title ?></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        
		<link rel="stylesheet" href="/css/style.css">
		<?php if (isset($stylesheets) && is_array($stylesheets)): ?>
            <?php foreach ($stylesheets as $_n=>$_stylesheet): ?>
            <link rel="stylesheet" href="/css/<?php echo $_stylesheet; ?>.css">
            <?php endforeach; ?>
		<?php endif ?>
		<!--[if lt IE 9]>
        <script src="/js/html5shiv.js"></script>
        <![endif]-->
	</head>
<?php
/**
 * Load some layout vars
 */
$_ownDcost = array();
$_sprices = $record->getSpecialPrices();
$_stocks = R::find('stock', " billnumber = ? ORDER BY earmark, mfa DESC, weight DESC ", array($record->invoice->name));
?>
<body>
    <div class="invoice-wrapper">
    <div class="company-header">
        <div class="name-logo">
            <?php echo htmlspecialchars($record->invoice->company->legalname) ?>
        </div>
        <div class="column communication">
            <table>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_phone') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->phone) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_fax') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->fax) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_email') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->email) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_website') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->website) ?></td>
                </tr>
            </table>
        </div>
        <div class="column postaladdress">
            <table>
                <tr>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->street) ?></td>
                </tr>
                <tr>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->zip) ?> <?php echo htmlspecialchars($record->invoice->company->city) ?></td>
                </tr>
            </table>
        </div>
        <div class="column taxid">
            <table>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_taxoffice') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->taxoffice) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_taxid') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->taxid) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_vatid') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->vatid) ?></td>
                </tr>
            </table>
        </div>
        <div class="column bankdata">
            <table>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_bankname') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankname) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_bankcode') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankcode) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_bankaccount') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankaccount) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_bic') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->bic) ?></td>
                </tr>
                <tr>
                    <td class="label"><?php echo I18n::__('company_label_iban') ?></td>
                    <td class="value"><?php echo htmlspecialchars($record->invoice->company->iban) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="addressinfowrapper">
    <div class="addresslabel">
        <div class="senderline">
        <?php echo htmlspecialchars($record->invoice->company->getSenderline()) ?>
        </div>
        <div class="name">
        <?php echo htmlspecialchars($record->person->name) ?>
        </div>
        <div class="postal">
            <p>
            <?php echo nl2br(htmlspecialchars($record->person->getAddress('billing')->getFormattedAddress())) ?>
            </p>
        </div>
    </div>
    <div class="infolabel">
        <table class="invoice">
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_serial') ?></label>
                <td class="value"><?php echo $record->invoice->name ?></label>
            </tr>
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_person') ?></label>
                <td class="value"><?php echo $record->person->account ?></label>
            </tr>
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_nickname') ?></label>
                <td class="value"><?php echo $record->person->nickname ?></label>
            </tr>
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_bookingdate') ?></label>
                <td class="value"><?php echo $record->invoice->localizedDate('bookingdate') ?></label>
            </tr>
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_slaughterdate') ?></label>
                <td class="value"><?php echo $record->csb->localizedDate('pubdate') ?></label>
            </tr>
        </table>
    </div>
    </div>
    <div class="summary">
    <table class="invoice internal">
        <thead>
            <tr>
                <th><?php echo I18n::__('invoice_internal_label_supplier') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_piggery') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_dprice') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_totalweight') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_totalnet') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="total dealer">
                <td><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="number"><?php echo $record->piggery ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('dprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('totalweight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('totalnet', 2)) ?></td>
            </tr>
            <tr class="mean dealer">
                <td><?php echo I18n::__('invoice_internal_label_dealermean') ?></td>
                <td class="number">&nbsp;</td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('meandprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?></td>
                <td class="number">&nbsp;</td>
            </tr>

            <?php if ( count ( $_ownDcost ) ): ?>
            <tr>
                <td colspan="5" class="section"><?php echo I18n::__('invoice_internal_label_cost') ?></td>
            </tr>
                <?php foreach ($_ownDcost as $_cost_id => $_cost): ?>
            <tr>
                <td><?php echo I18n::__('cost_label_' . $_cost->label) ?></td>
                <td class="number"><?php echo htmlspecialchars($_cost->decimal('factor', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_cost->decimal('value', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_cost->content) ?></td>
                <td class="number"><?php echo htmlspecialchars($_cost->decimal('net', 2)) ?></td>
            </tr>
                <?php endforeach ?>
            <tr>
                <td colspan="4" class="number"><?php echo I18n::__('wawi_label_cost_sum') ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('totalcost', 2)) ?></td>
            </tr>
            <?php endif ?>
            <tr>
                <td colspan="4" class="number section"><?php echo I18n::__('wawi_label_net') ?></td>
                <td class="number section"><?php echo htmlspecialchars($record->decimal('subtotalnet', 2)) ?></td>
            </tr>
            <tr>
                <td colspan="4" class="number"><?php echo htmlspecialchars($record->person->vat->name) ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('vatvalue', 2)) ?></td>
            </tr>
            <tr class="gros">
                <td colspan="4" class="number"><?php echo I18n::__('wawi_label_gros') ?></td>
                <td class="number"><?php echo htmlspecialchars($record->decimal('totalgros', 2)) ?></td>
            </tr>
            
            <?php if ( count ( $_sprices) ): ?>
            <tr class="info">
                <td colspan="5" class="section"><?php echo I18n::__('invoice_internal_label_damage') ?></td>
            </tr>
                <?php foreach ($_sprices as $_sprice_id => $_sprice): ?>
            <tr class="info">
                <td><?php echo htmlspecialchars($_sprice->note) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sprice->decimal('piggery', 0)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sprice->decimal('dprice', 3)) ?></td>
                <td class="number">&nbsp;</td>
                <td class="number">&nbsp;</td>
            </tr>
                <?php endforeach ?>
            <?php endif ?>
            
        </tfoot>
        <tbody>
    <?php foreach ($record->with(' ORDER BY earmark ')->ownDeliverer as $_sub_id => $_sub): ?>
            <tr>
                <td><?php echo $_sub->earmark ?></td>
                <td class="number"><?php echo $_sub->piggery ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('dprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('totalnet', 2)) ?></td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    </div>
    <div class="details">
    <table class="invoice internal stock">
        <thead>
            <tr>
                <th><?php echo I18n::__('invoice_internal_label_earmark') ?></th>
                <th><?php echo I18n::__('invoice_internal_label_name') ?></th>
                <th><?php echo I18n::__('invoice_internal_label_quality') ?></th>
                <th><?php echo I18n::__('invoice_internal_label_damagestock') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_mfa') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_weight') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_dprice') ?></th>
                <th class="number"><?php echo I18n::__('invoice_internal_label_dtotal') ?></th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($_stocks as $_stock_id => $_stock): ?>
            <tr>
                <td><?php echo $_stock->earmark ?></td>
                <td><?php echo $_stock->name ?></td>
                <td><?php echo $_stock->quality ?></td>
                <td><?php echo $_stock->getDamageAsText() ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('mfa', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('weight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('dprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('totaldprice', 2)) ?></td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    </div>
    </div>
    </body>
</html>