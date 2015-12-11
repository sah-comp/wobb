<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 10pt;
        }
        p {
            margin: 0pt 0pt 20pt 0pt;
        }
        .senderline {
            font-size: 6pt;
            border-bottom: 0.1mm solid #000000;
        }
        .name,
        .postal {
            font-size: 11pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 11pt;
        }
        .uberemphasize {
            font-size: 14pt;
            font-weight: bold;
        }
        .dinky {
            font-size: 8pt;
        }
        .moredinky {
            font-size: 6pt;
        }
        .centered {
            text-align: center;
        }
        table {
            border-collapse: collapse;
        }
        th {
            text-align: left;
        }
        td.bt {
            border-top: 0.1mm solid #000000;
        }
        td.br {
            border-right: 0.1mm solid #000000;
        }
        th,
        td.bb {
            border-bottom: 0.1mm solid #000000;
        }
        th.number,
        td.number {
            text-align: right;
        }
        table.info td.label,
        table.info td.value {
            text-align: right;
        }
        table.pageheader td.label,
        table.pageheader td.value {
            font-size: 6pt;
        }
        table.pageheader td.label {
            text-align: right;
        }
        table.pageheader td.value {
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
        table.stock th,
        table.stock td {
            font-size: 8pt;
        }
        
        table.stock caption span {
            font-weight: bold;
            padding-bottom: 0.2em;
        }
        
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="dealerheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="50%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->person->name) ?></td>
                <td width="50%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_service', null, array($record->invoice->name, $pubdate)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="dealerfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="dealerheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="dealerfooter" value="on" />
    mpdf-->

    <div style="height: 5mm;"></div>

<?php
/**
 * Get all deliverers and the last one to learn about page break in the loop.
 */
$deliverers = $record->with(' ORDER BY earmark ')->ownDeliverer;
$end_sub = end($deliverers);
?>
<?php foreach ($deliverers as $_sub_id => $_sub): ?>    
    <table width="100%" class="stock">
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_earmark') ?></th>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_name') ?></th>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_quality') ?></th>
                <th width="15%"><?php echo I18n::__('invoice_internal_label_damagestock') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_internal_label_qs') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_internal_label_mfa') ?></th>
                <th width="12.5%" class="number"><?php echo I18n::__('invoice_internal_label_weight') ?></th>
                <th width="12.5%" class="number"><?php echo I18n::__('invoice_internal_label_sprice') ?></th>
                <th width="15%" class="number"><?php echo I18n::__('invoice_internal_label_dtotal') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td class="bt bb"><?php echo I18n::__('invoice_internal_label_dealermean') ?></td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meanmfa', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meanweight', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meansprice', 3)) ?></td>
                <td class="bt bb">&nbsp;</td>
            </tr>
            <tr>
                <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="bt emphasize"><?php echo htmlspecialchars($_sub->piggery) ?></td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
                <td class="bt">&nbsp;</td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($_sub->decimal('totalnetsprice', 2)) ?></td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach (R::find('stock', " earmark = :earmark AND csb_id = :csb_id ORDER BY mfa DESC, weight DESC ", array(':earmark' => $_sub->earmark, ':csb_id' => $record->csb_id)) as $_stock_id => $_stock): ?>
            <tr>
                <td><?php echo $_stock->earmark ?></td>
                <td><?php echo $_stock->name ?></td>
                <td><?php echo $_stock->quality ?></td>
                <td><?php echo $_stock->getDamageAsText() ?></td>
                <td><?php echo $_stock->getQsAsText() ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('mfa', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('weight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('sprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('totalsprice', 2)) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    
    <?php if ( $end_sub->getId() != $_sub->getId() ): ?>
    <!--mpdf
    <pagebreak resetpagenum="1" />
    mpdf-->
    <?php endif ?>
<?php endforeach; ?>
</body>
</html>
