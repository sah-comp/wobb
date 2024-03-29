<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        table.comparison {
            font-size: 13pt;
        }
        .notemphasized {
            font-weight: normal;
            font-size: 7pt;
            color: #666666;
        }
        .gap {
            padding-top: 10mm;
        }
        .emphasize {
            font-weight: bold;
            font-size: 15pt;
        }
        .uberemphasize {
            font-size: 18pt;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
        }
        caption {
            font-weight: bold;
            padding-bottom: 3mm;
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
    </style>
</head>
<body>
    <!--mpdf
    <htmlpageheader name="tkheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($record->company->legalname) ?></td>
                <td width="40%" style="text-align: right;"><?php echo I18n::__('comparison_text_header', null, [$startdate, $record->person->nickname]) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="tkfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nbpg}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="tkheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="tkfooter" value="on" />
    mpdf-->

    <div style="height: 10mm;"></div>

    <table class="comparison" width="100%">
        <caption>
            <?php echo I18n::__('comparison_caption_total', null, [$startdate, $enddate, $record->decimal('baseprice', 3)]) ?>
        </caption>
        <thead>
            <tr>
                <th width="20%"><?php echo I18n::__('comparison_label_person') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('comparison_label_piggery') ?></th>
                <th width="15%" class="number"><?php echo I18n::__('comparison_label_totalweight') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('comparison_label_meanmfa') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('comparison_label_meanweight') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('comparison_label_diff') ?></th>
                <th width="15%" class="number"><?php echo I18n::__('comparison_label_totalnet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('comparison_label_avgprice') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bb emphasize" style="white-space: nowrap;"><?php echo htmlspecialchars($record->person->nickname . ' ' . $record->person->name) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('piggery', 0)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('totalweight', 2)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('meanmfa', 2)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?></td>
                <td class="bb number">&nbsp;</td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('totalnet', 2)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($record->decimal('avgprice', 3)) ?></td>
            </tr>
            <tr>
                <td class="bb" colspan="8">&nbsp;</td>
            </tr>
        <?php $_deliverers = $record->getDeliverers() ?>
        <?php foreach ($_deliverers as $_id => $_deliverer) : ?>
            <tr>
                <td class="bb" style="white-space: nowrap;"><?php echo htmlspecialchars($_deliverer->person->nickname . ' ' . $_deliverer->person->name) ?></td>
                <td class="bb" colspan="4">&nbsp;</td>
                <td class="bb number"><?php echo htmlspecialchars($_deliverer->decimal('diff', 2)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($_deliverer->decimal('totalnet', 2)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($_deliverer->decimal('avgprice', 3)) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

</body>
</html>
