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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('adjustment_text_header', null, array($pubdate)) ?></td>
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
    
    <table class="adjustment" width="100%">
        <thead>
            <tr>
                <th width="5%"><?php echo I18n::__('adjustment_label_invoice') ?></th>
                <th width="10%"><?php echo I18n::__('adjustment_label_invoice_bookingdate') ?></th>
                <th width="25%"><?php echo I18n::__('adjustment_label_person') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('adjustment_label_net') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('adjustment_label_vatvalue') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('adjustment_label_gros') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($record->with(' ORDER BY id ')->ownAdjustmentitem as $_id => $_adjustmentitem): ?>
            <tr>
                <td><?php echo htmlspecialchars($_adjustmentitem->invoice()->name) ?></td>
                <td><?php echo htmlspecialchars($_adjustmentitem->invoice()->localizedDate('bookingdate')) ?></td>
                <td><?php echo htmlspecialchars($_adjustmentitem->invoice()->getPersonName(15)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_adjustmentitem->decimal('net', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_adjustmentitem->decimal('vatvalue', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_adjustmentitem->decimal('gros', 2)) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize"><?php echo I18n::__('adjustment_label_total') ?></td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('net', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('vatvalue', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('gros', 2)) ?></td>
            </tr>
        </tbody>
    </table>    
</body>
</html>
