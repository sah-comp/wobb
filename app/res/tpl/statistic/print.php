<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 10pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 11pt;
        }
        .uberemphasize {
            font-size: 14pt;
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('lanuv_text_header', null, array($startdate, $enddate)) ?></td>
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
    
    <table class="analysis" width="100%">
        <caption>
            <?php echo I18n::__('lanuv_caption_total', null, array( $record->weekOfYear() )) ?>
        </caption>
        <thead>
            <tr>
                <th width="10%"><?php echo I18n::__('lanuv_label_quality') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('lanuv_label_piggery') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('lanuv_label_piggerypercentage') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('lanuv_label_sumweight') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('lanuv_label_sumtotaldprice') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('lanuv_label_avgmfa') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('lanuv_label_avgweight') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('lanuv_label_avgpricelanuv') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($record->with(' ORDER BY id ')->ownLanuvitem as $_id => $_lanuvitem): ?>
            <tr>
                <td><?php echo htmlspecialchars($_lanuvitem->quality) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('piggery', 0)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('piggerypercentage', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('sumweight', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('sumtotallanuvprice', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('avgmfa', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('avgweight', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_lanuvitem->decimal('avgpricelanuv', 3)) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize"><?php echo I18n::__('lanuv_label_total') ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('piggery', 0)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('piggerypercentage', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('sumweight', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('sumtotallanuvprice', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgmfa', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgweight', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgpricelanuv', 3)) ?></td>
            </tr>
        </tbody>
    </table>
    
</body>
</html>
