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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_weightlist', null, array($record->invoice->name, $bookingdate)) ?></td>
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
                        <td class="label"><?php echo I18n::__('invoice_internal_label_slaughterdate') ?></td>
                        <td class="value"><?php echo $pubdate ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_nickname') ?></td>
                        <td class="value"><?php echo $record->person->nickname ?></td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_person') ?></td>
                        <td class="value"><?php echo $record->person->account ?></td>
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
                <th width="10%" class="number"><?php echo I18n::__('invoice_internal_label_itw') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_internal_label_qs') ?></th>
                <th width="30%" class="number"><?php echo I18n::__('invoice_internal_label_piggery') ?></th>
                <th width="25%" class="number"><?php echo I18n::__('invoice_internal_label_mfa') ?></th>
                <th width="25%" class="number"><?php echo I18n::__('invoice_internal_label_weight') ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td class="bt bb"><?php echo I18n::__('invoice_internal_label_dealermean') ?></td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanmfa', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?></td>
            </tr>
            <tr>
                <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                <td class="bt emphasize number"><?php echo $record->itwpiggery ?></td>
                <td class="bt emphasize number"><?php echo $record->qspiggery ?></td>
                <td class="bt emphasize number"><?php echo $record->piggery ?></td>
                <td class="bt emphasize number">&nbsp;</td>
                <td class="bt emphasize number"><?php echo htmlspecialchars($record->decimal('totalweight', 2)) ?></td>
            </tr>
        </tfoot>
        <tbody>
    <?php foreach ($record->with(' ORDER BY earmark ')->ownDeliverer as $_sub_id => $_sub) : ?>
            <tr>
                <td><?php echo $_sub->earmark ?></td>
                <td class="number"><?php echo $_sub->itwpiggery ?></td>
                <td class="number"><?php echo $_sub->qspiggery ?></td>
                <td class="number"><?php echo $_sub->piggery ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('meanmfa', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
            </tr>
    <?php endforeach ?>
        </tbody>
    </table>

    <!--mpdf
    <pagebreak resetpagenum="1" />
    <htmlpageheader name="dealerheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->person->name) ?></td>
                <td width="40%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_weightlist', null, array($record->invoice->name, $pubdate)) ?></td>
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
    <?php foreach ($deliverers as $_sub_id => $_sub) : ?>
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
                </tr>
                <tr>
                    <td class="bt emphasize"><?php echo I18n::__('invoice_internal_label_dealertotal') ?></td>
                    <td class="bt emphasize"><?php echo htmlspecialchars($_sub->piggery) ?></td>
                    <td class="bt">&nbsp;</td>
                    <td class="bt">&nbsp;</td>
                    <td class="bt">&nbsp;</td>
                    <td class="bt">&nbsp;</td>
                    <td class="bt emphasize number"><?php echo htmlspecialchars($_sub->decimal('totalweight', 2)) ?></td>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach (R::find('stock', " earmark = :earmark AND csb_id = :csb_id ORDER BY mfa DESC, weight DESC ", array(':earmark' => $_sub->earmark, ':csb_id' => $record->csb_id)) as $_stock_id => $_stock) : ?>
                <tr>
                    <td><?php echo $_stock->earmark ?></td>
                    <td><?php echo $_stock->name ?></td>
                    <td><?php echo $_stock->quality ?></td>
                    <td><?php echo $_stock->getDamageAsText() ?></td>
                    <td><?php echo $_stock->getQsAsText() ?></td>
                    <td class="number"><?php echo htmlspecialchars($_stock->decimal('mfa', 2)) ?></td>
                    <td class="number"><?php echo htmlspecialchars($_stock->decimal('weight', 2)) ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($end_sub->getId() != $_sub->getId()) : ?>
        <!--mpdf
        <pagebreak resetpagenum="1" />
        mpdf-->
        <?php endif ?>
    <?php endforeach; ?>
</body>
</html>
