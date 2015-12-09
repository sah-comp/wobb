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
        
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="dealerheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="50%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->person->name()) ?></td>
                <td width="50%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_voucher', null, array($record->invoice->name, $bookingdate)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="dealerfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nb}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="dealerheader" value="on" />
    <sethtmlpagefooter name="dealerfooter" value="on" />
    mpdf-->
    
    <table width="100%">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <?php if ( $specialprices || $nonqs ): ?>
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="5" class="dinky number"><?php echo I18n::__('invoice_internal_label_specialprice') ?></td>
                        </tr>
                        <tr>
                            <th class="dinky number"><?php echo I18n::__('invoice_internal_label_piggery') ?></th>
                            <th class="dinky"><?php echo I18n::__('invoice_internal_label_description') ?></th>
                            <th class="dinky"><?php echo I18n::__('invoice_internal_label_code') ?></th>
                            <th class="dinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="dinky number"><?php echo I18n::__('invoice_internal_label_unitprice') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach ($specialprices as $_id => $_specialprice): ?>
                        <tr>
                            <td class="dinky number"><?php echo htmlspecialchars($_specialprice->piggery) ?></td>
                            <td class="dinky"><?php echo htmlspecialchars($_specialprice->note) ?></td>
                            <td class="dinky"><?php echo htmlspecialchars($_specialprice->name) ?></td>
                            <td class="dinky"><?php echo htmlspecialchars(I18n::__('var_condition_' . $_specialprice->condition)) ?></td>
                            <td class="dinky number"><?php echo $_specialprice->decimal('sprice', 3) ?></td>
                        </tr>
                <?php endforeach ?>
                <?php if ( $nonqs ): ?>
                        <tr>
                            <td class="dinky number"><?php echo htmlspecialchars($nonqs) ?></td>
                            <td class="dinky"><?php echo I18n::__('invoice_internal_label_nonqs') ?></td>
                            <td></td>
                            <td class="dinky"><?php echo I18n::__('invoice_internal_method_nonqs') ?></td>
                            <td class="dinky number"><?php echo $record->person->decimal('qsdiscount', 3) ?></td>
                        </tr>
                <?php endif ?>
                    </tbody>
                </table>
                <?php endif ?>
            </td>
        </tr>
    </table>
    
    <div style="height: 5mm;"></div>
    
    <table width="60%">
        <tr>
            <td colspan="3" class="dinky centered"><?php echo I18n::__('invoice_internal_label_pricing') ?></td>
        </tr>
        <tr>
            <td width="33.3%" class="br" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_weightmargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->person->pricing->withCondition(" kind='weight' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
            <td width="33.3%" class="br" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfamargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->person->pricing->withCondition(" kind='mfa' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
            <td width="33.3%" class="br" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfasubmargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->person->pricing->withCondition(" kind='mfasub' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
    
    <div style="height: 5mm;"></div>

<?php foreach ($record->with(' ORDER BY earmark ')->ownDeliverer as $_sub_id => $_sub): ?>
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
    <div class="page-break"></div>
<?php endforeach; ?>
</body>
</html>
