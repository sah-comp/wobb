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
?>
<body>
    <div class="invoice-wrapper">
    <div class="addresslabel">
        <div class="senderline">
        <?php echo htmlspecialchars($record->invoice->company->senderline) ?>
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
                <td class="value"><?php echo $record->invoice->bookingdate ?></label>
            </tr>
            <tr>
                <td class="label"><?php echo I18n::__('invoice_internal_label_slaughterdate') ?></label>
                <td class="value"><?php echo $record->csb->pubdate ?></label>
            </tr>
        </table>
    </div>
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
                <td colspan="4" class="number"><?php echo htmlspecialchars($record->person->vat->name) ?> <span class="info"><?php echo I18n::__('wawi_vat_info_must_payback_if_lower') ?></span></td>
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
    </body>
</html>