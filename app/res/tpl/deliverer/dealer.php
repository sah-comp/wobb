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
    <htmlpageheader name="tkheader-firstpage" style="display: none;">
        <table width="100%">
            <tr>
                <td style="vertical-align: top; width: 120px;">
                    <img src="img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
                        <tr>
                            <td class="value">
                                <?php echo htmlspecialchars($record->invoice->company->legalname) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="value">
                            <?php echo htmlspecialchars($record->invoice->company->street) ?></td>
                        </tr>
                        <tr>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->zip) ?> <?php echo htmlspecialchars($record->invoice->company->city) ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
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
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
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
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
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
                </td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpageheader name="tkheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->company->legalname) ?></td>
                <td width="40%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_voucher', null, array($record->invoice->name, $bookingdate)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="tkfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="tkheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="tkheader" value="on" />
    <sethtmlpagefooter name="tkfooter" value="on" />
    mpdf-->

    <div style="height: 25mm;"></div>
    <table width="100%">
        <tr>
            <td style="width: 95mm; vertical-align: top;">
                <div class="senderline">
                    <?php echo htmlspecialchars($record->invoice->company->getSenderline()) ?>
                    <br /><br />
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
            <td style="width: 65mm; vertical-align: top;">
                <table class="info" width="100%">
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_serial') ?></td>
                        <td class="value emphasize"><?php echo $record->invoice->name ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_bookingdate') ?></td>
                        <td class="value"><?php echo $bookingdate ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_slaughterdate') ?></td>
                        <td class="value"><?php echo $pubdate ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_vezgprice') ?></td>
                        <td class="value"><?php echo $record->csb->decimal('baseprice', 2) ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_nickname') ?></td>
                        <td class="value"><?php echo $record->person->nickname ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_person') ?></td>
                        <td class="value"><?php echo $record->person->account ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_taxid') ?></td>
                        <td class="value"><?php echo $record->person->taxid ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="height: 18mm;"></div>

    <table class="deliverer" width="100%">
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_earmark') ?></th>
                <th width="5%" class="number"><?php echo I18n::__('invoice_internal_label_itw') ?></th>
                <th width="5%" class="number"><?php echo I18n::__('invoice_internal_label_qs') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_piggery') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_baseprice') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_weight') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_totalmerch') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td class="bt bb"><?php echo I18n::__('invoice_internal_label_dealermean') ?></td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meandprice', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?></td>
                <td class="bt bb number">&nbsp;</td>
            </tr>
            <tr>
                <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="bt emphasize number"><?php echo $record->itwpiggery ?></td>
                <td class="bt emphasize number"><?php echo $record->qspiggery ?></td>
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
                <td class="number"><?php echo $_sub->itwpiggery ?></td>
                <td class="number"><?php echo $_sub->qspiggery ?></td>
                <td class="number"><?php echo $_sub->piggery ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('dprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('totalnet', 2)) ?></td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    <div style="height: 5mm;"></div>
    <?php if ($conditions): ?>
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
            <?php foreach ($conditions as $_id => $_condition): ?>
            <tr>
                <td colspan="2">
                    <?php echo $_condition->content ?>
                </td>
                <td class="number">
                    <?php echo $_condition->decimal('value', 3) ?>
                </td>
                <td class="number">
                    <?php echo $_condition->decimal('factor', 2); ?>
                </td>
                <td class="number">
                    <?php echo $_condition->decimal('net', 2); ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>
    <?php if ($costs): ?>
    <table width="100%">
        <thead>
            <tr>
                <th width="40%" colspan="2"><?php echo I18n::__('invoice_internal_label_cost') ?></th>
                <?php if (! $conditions): ?>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_unitprice') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_unit') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('invoice_internal_label_total') ?></th>
                <?php else: ?>
                <th width="20%" class="number">&nbsp;</th>
                <th width="20%" class="number">&nbsp;</th>
                <th width="20%" class="number">&nbsp;</th>
                <?php endif ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td width="80%" class="bt number" colspan="4"><?php echo I18n::__('invoice_internal_label_costnet') ?></td>
                <td width="20%" class="bt number emphasize"><?php echo $record->invoice->decimal('costnet', 2) ?></td>
            </tr>
        </tfoot>
        <tbody>
    <?php foreach ($costs as $_id => $_cost): ?>
            <tr>
                <td colspan="2">
                    <?php echo $_cost->content, ' ', I18n::__('cost_label_'.$_cost->label) ?>
                </td>
                <td class="number">
                    <?php echo $_cost->decimal('value', 3) ?>
                </td>
                <td class="number">
                <?php if ($_cost->label == 'stockperitem'): ?>
                    <?php
                        $_value = $record->piggery * $_cost->value;
                        echo $record->piggery;
                    ?>
                <?php elseif ($_cost->label == 'stockperweight'): ?>
                    <?php
                        $_value = $record->totalweight * $_cost->value;
                        echo $record->decimal('totalweight', 2);
                    ?>
                <?php elseif ($_cost->label == 'flat'): ?>
                    <?php
                        $_value = $_cost->value;
                        echo "&nbsp;";
                    ?>
                <?php endif ?>
                </td>
                <td class="number">
                    <?php echo number_format($_value, 2, ',', '.') ?>
                </td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>
    <div style="height: 5mm;"></div>
    <?php endif ?>

    <table width="100%">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <?php if ($specialprices || $nonqs): ?>
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
                            <td class="dinky number"><?php echo $_specialprice->decimal('dprice', 3) ?></td>
                        </tr>
                <?php endforeach ?>
                <?php if ($nonqs): ?>
                        <tr>
                            <td class="dinky number"><?php echo htmlspecialchars($nonqs) ?></td>
                            <td class="dinky"><?php echo I18n::__('invoice_internal_label_nonqs') ?></td>
                            <td>&nbsp;</td>
                            <td class="dinky"><?php echo I18n::__('invoice_internal_method_nonqs') ?></td>
                            <td class="dinky number"><?php echo $record->person->decimal('qsdiscount', 3) ?></td>
                        </tr>
                <?php endif ?>
                    </tbody>
                </table>
                <?php endif ?>
            </td>
            <td width="10%"></td>
            <td width="50%" style="vertical-align: top;">
                <table width="100%">
                    <tr>
                        <td width="50%" class="bb number"><?php echo I18n::__('wawi_label_net') ?></td>
                        <td width="50%" class="bb emphasize number"><?php echo $record->invoice->decimal('subtotalnet', 2) ?></td>
                    </tr>
                    <tr>
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->person->vat->name) ?></td>
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->invoice->decimal('vatvalue', 2)) ?></td>
                    </tr>
                    <?php if ($record->invoice->company->hastierwohl && $record->itwpiggery > 0 && ($record->invoice->totalnetitw > 0 || $record->invoice->totalnetitw < 0)): ?>
                    <tr>
                        <td width="50%" class="bb number"><?php echo I18n::__('wawi_label_net_itw') ?></td>
                        <td width="50%" class="bb number"><?php echo $record->invoice->decimal('totalnetitw', 2) ?></td>
                    </tr>
                    <tr>
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->invoice->company->vat->name) ?></td>
                        <td width="50%" class="bt bb number"><?php echo htmlspecialchars($record->invoice->decimal('vatvalueitw', 2)) ?></td>
                    </tr>
                    <?php endif ?>
                    <tr>
                        <td width="50%" class="bt number"><?php echo I18n::__('wawi_label_gros') ?></td>
                        <td width="50%" class="bt uberemphasize number"><?php echo htmlspecialchars($record->invoice->decimal('totalgros', 2)) ?></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    <div style="height: 5mm;"></div>

    <?php Flight::render('deliverer/dealer/pricemask', array(
        'record' => $record
    )) ?>

    <div style="height: 5mm;"></div>

    <table width="60%">
        <tr>
            <td class="dinky" style="vertical-align: top;">
                <?php echo Flight::textile(I18n::__('invoice_internal_text_legal')) ?>
            </td>
        </tr>
    </table>
    <div class="page-break"></div>
<?php foreach ($record->with(' ORDER BY earmark ')->ownDeliverer as $_sub_id => $_sub): ?>
    <table width="100%" class="stock">
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_earmark') ?></th>
                <th width="10%"><?php echo I18n::__('invoice_internal_label_name') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_internal_label_quality') ?></th>
                <th width="15%"><?php echo I18n::__('invoice_internal_label_damagestock') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_internal_label_itw') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_internal_label_qs') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_internal_label_mfa') ?></th>
                <th width="12.5%" class="number"><?php echo I18n::__('invoice_internal_label_weight') ?></th>
                <th width="12.5%" class="number"><?php echo I18n::__('invoice_internal_label_dprice') ?></th>
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
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meanmfa', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meanweight', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($_sub->decimal('meandprice', 3)) ?></td>
                <td class="bt bb">&nbsp;</td>
            </tr>
            <tr>
                <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="bt emphasize"><?php echo htmlspecialchars($_sub->piggery) ?></td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt">&nbsp;</td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
                <td class="bt">&nbsp;</td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($_sub->decimal('totalnet', 2)) ?></td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach (R::find('stock', " earmark = :earmark AND csb_id = :csb_id ORDER BY mfa DESC, weight DESC ", array(':earmark' => $_sub->earmark, ':csb_id' => $record->csb_id)) as $_stock_id => $_stock): ?>
            <tr>
                <td><?php echo $_stock->earmark ?></td>
                <td><?php echo $_stock->name ?></td>
                <td><?php echo $_stock->quality ?></td>
                <td><?php echo $_stock->getDamageAsText() ?></td>
                <td><?php echo $_stock->getItwAsText() ?></td>
                <td><?php echo $_stock->getQsAsText() ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('mfa', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('weight', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('dprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_stock->decimal('totaldprice', 2)) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <div style="height: 5mm;"></div>
<?php endforeach; ?>
<?php if ($record->invoice->person->hasservice): ?>
        <!--mpdf
        <pagebreak resetpagenum="1" />
        <htmlpageheader name="dealerheader" style="display: none;">
            <table width="100%">
                <tr>
                    <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->person->name) ?></td>
                    <td width="40%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_service', null, array($record->invoice->name, $pubdate)) ?></td>
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

        <?php Flight::render('deliverer/dealer/pricemask', array(
            'record' => $record
        )) ?>

        <div style="height: 5mm;"></div>

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

        <?php if ($end_sub->getId() != $_sub->getId()): ?>
        <!--mpdf
        <pagebreak resetpagenum="1" />
        mpdf-->
        <?php endif ?>
    <?php endforeach; ?>
<?php endif ?>
</body>
</html>
