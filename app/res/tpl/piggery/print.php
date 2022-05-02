<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 9pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 10pt;
        }
        .uberemphasize {
            font-size: 13pt;
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('piggery_text_header', null, array($startdate, $enddate)) ?></td>
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

    <table class="piggery" width="100%">
        <caption>
            <?php echo I18n::__('piggery_caption_total') ?>
        </caption>
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('piggery_label_pubdate') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('piggery_label_piggery') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($record->with(' ORDER BY pubdate ')->ownPiggeryitem as $_id => $_pi): ?>
            <tr>
                <td><?php echo htmlspecialchars($_pi->localizedDate('pubdate')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_pi->decimal('stockcount', 0)) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize"><?php echo I18n::__('piggery_label_total') ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('stockcount', 0)) ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
