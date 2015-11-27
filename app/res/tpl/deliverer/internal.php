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
            margin: 0pt;
        }
        .senderline {
            font-size: 6pt;
            border-bottom: 0.1mm solid #000000;
            margin-bottom: 3mm;
        }
        .name,
        .postal {
            font-size: 11pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 11pt;
        }
        .dinky {
            font-size: 8pt;
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
        
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="tkheader-firstpage" style="display: none;">
        <table width="100%">
            <tr>
                <td style="text-align: right; font-size: 14pt; font-weight: bold;">
                    <?php echo htmlspecialchars($record->invoice->company->legalname) ?>
                </td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpageheader name="tkheader" style="display: none;">
        <table width="100%">
            <tr>
                <td>Gutschrift</td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="tkfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nb}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="tkheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="tkheader" value="on" />
    <sethtmlpagefooter name="tkfooter" value="on" />
    mpdf-->
    
    <div style="height: 30mm;"></div>
    <table width="100%">
        <tr>
            <td style="width: 85mm; vertical-align: top;">
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
            </td>
            <td style="width: 75mm; vertical-align: top;">
                <table class="info" width="100%">
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_serial') ?></label>
                        <td class="value"><?php echo $record->invoice->name ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_bookingdate') ?></label>
                        <td class="value"><?php echo $record->invoice->localizedDate('bookingdate') ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_slaughterdate') ?></label>
                        <td class="value"><?php echo $record->csb->localizedDate('pubdate') ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_nickname') ?></label>
                        <td class="value"><?php echo $record->person->nickname ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_person') ?></label>
                        <td class="value"><?php echo $record->person->account ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_taxid') ?></label>
                        <td class="value"><?php echo $record->person->taxid ?></label>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <div style="height: 10mm;"></div>
    
    <table class="deliverer" width="100%">
        <thead>
            <tr>
                <th width="20%">Ohrmarke</th>
                <th width="20%" class="number">Anzahl</th>
                <th width="20%" class="number">Basispreis</th>
                <th width="20%" class="number">Gewicht KG</th>
                <th width="20%" class="number">Wert in Euro</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td class="bt bb"><?php echo I18n::__('invoice_internal_label_dealermean') ?></td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meandprice', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?></td>
                <td class="bt bb number">&nbsp;</td>
            </tr>
            <tr>
                <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="bt emphasize number"><?php echo $record->piggery ?></td>
                <td class="bt number"></td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($record->decimal('totalweight', 2)) ?></td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($record->decimal('totalnet', 2)) ?></td>
            </tr>
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
    
    <div style="height: 5mm;"></div>
    
    <table width="100%">
        <thead>
            <tr>
                <th width="40%" colspan="2"><?php echo I18n::__('invoice_internal_label_condition') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_unitprice') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_unit') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_total') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td width="80%" class="bt number" colspan="4"><?php echo I18n::__('invoice_internal_label_bonusnet') ?></td>
                <td width="20%" class="bt number emphasize"><?php echo $record->invoice->decimal('bonusnet', 2) ?></td>
            </tr>
        </tfoot>
        <tbody>
    <?php foreach ($record->person->ownCondition as $_id => $_condition): ?>
            <tr>
                <td colspan="2">
                    <?php echo $_condition->content, ' ', I18n::__('condition_label_'.$_condition->label) ?>
                </td>
                <td class="number">
                    <?php echo $_condition->decimal('value', 3) ?>
                </td>
                <td class="number">
                <?php if ( $_condition->label == 'stockperitem' ): ?>
                    <?php
                        $_value = $record->piggery * $_condition->value;
                        echo $record->piggery;
                    ?>
                <?php elseif ( $_condition->label == 'stockperweight' ): ?>
                    <?php
                        $_value = $record->totalweight * $_condition->value;
                        echo $record->decimal('totalweight', 2);
                    ?>
                <?php endif ?>
                </td>
                <td class="number">
                    <?php echo number_format( $_value, 2, ',', '.') ?>
                </td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <th width="40%" colspan="2"><?php echo I18n::__('invoice_internal_label_cost') ?></th>
                <th width="20%" class="number">&nbsp;</th>
                <th width="20%" class="number">&nbsp;</th>
                <th width="20%" class="number">&nbsp;</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td width="80%" class="bt number" colspan="4"><?php echo I18n::__('invoice_internal_label_costnet') ?></td>
                <td width="20%" class="bt number emphasize"><?php echo $record->invoice->decimal('costnet', 2) ?></td>
            </tr>
        </tfoot>
        <tbody>
    <?php foreach ($record->person->ownCost as $_id => $_cost): ?>
            <tr>
                <td colspan="2">
                    <?php echo $_cost->content, ' ', I18n::__('cost_label_'.$_cost->label) ?>
                </td>
                <td class="number">
                    <?php echo $_cost->decimal('value', 3) ?>
                </td>
                <td class="number">
                <?php if ( $_cost->label == 'stockperitem' ): ?>
                    <?php
                        $_value = $record->piggery * $_cost->value;
                        echo $record->piggery;
                    ?>
                <?php elseif ( $_cost->label == 'stockperweight' ): ?>
                    <?php
                        $_value = $record->totalweight * $_cost->value;
                        echo $record->decimal('totalweight', 2);
                    ?>
                <?php elseif ( $_cost->label == 'flat' ): ?>
                    <?php
                        $_value = $_cost->value;
                        echo "&nbsp;";
                    ?>
                <?php endif ?>
                </td>
                <td class="number">
                    <?php echo number_format( $_value, 2, ',', '.') ?>
                </td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    
    <div style="height: 5mm;"></div>
    
    <table width="100%">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="dinky number"><?php echo I18n::__('invoice_internal_label_specialprice') ?></td>
                        </tr>
                        <tr>
                            <th class="dinky number"><?php echo I18n::__('invoice_internal_label_piggery') ?></th>
                            <th class="dinky"><?php echo I18n::__('invoice_internal_label_description') ?></th>
                            <th class="dinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="dinky number"><?php echo I18n::__('invoice_internal_label_unitprice') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach ($record->with(" ORDER BY kind, piggery DESC ")->ownSpecialprice as $_id => $_specialprice): ?>
                        <tr>
                            <td class="dinky number"><?php echo htmlspecialchars($_specialprice->piggery) ?></td>
                            <td class="dinky"><?php echo htmlspecialchars($_specialprice->note) ?></td>
                            <td class="dinky"><?php echo htmlspecialchars(I18n::__('var_condition_' . $_specialprice->condition)) ?></td>
                            <td class="dinky number"><?php echo $_specialprice->decimal('dprice', 3) ?></td>
                        </tr>
                <?php endforeach ?>
                    </tbody>
                </table>
            </td>
            <td width="20%"></td>
            <td width="40%" style="vertical-align: top;">
                <table width="100%">
                    <tr>
                        <td width="50%" class="bb number"><?php echo I18n::__('wawi_label_net') ?></td>            
                        <td width="50%" class="bb emphasize number"><?php echo $record->invoice->decimal('subtotalnet', 2) ?></td>
                    </tr>
                    <tr>
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->person->vat->name) ?></td>            
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->invoice->decimal('vatvalue', 2)) ?></td>
                    </tr>
                    <tr>
                        <td width="50%" class="bt bb number"><?php echo I18n::__('wawi_label_gros') ?></td>            
                        <td width="50%" class="bt bb emphasize number"><?php echo htmlspecialchars($record->invoice->decimal('totalgros', 2)) ?></td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
