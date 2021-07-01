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
		.notemphasized {
			font-weight: normal;
			font-size: 7pt;
			color: #666666;
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('analysis_text_header', null, array($startdate, $enddate)) ?></td>
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

    <?php
    $_suppliers = $record->with(' ORDER BY id')->ownAnalysis;
    $_keys = array_keys($_suppliers);
    $_lastKey = array_pop($_keys);
    ?>

    <!-- Totals of each supplier -->
    <div style="height: 10mm;"></div>

    <table class="analysis" width="100%">
        <caption>
            <?php echo I18n::__('analysis_caption_suppliers') ?>
        </caption>
        <thead>
            <tr>
                <th width="9%"><?php echo I18n::__('analysis_label_supplier') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('analysis_label_piggery') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('analysis_label_piggerypercentage') ?></th>
                <th width="5%" class="number"><?php echo I18n::__('analysis_label_itwpiggery') ?></th>
                <th width="15%" class="number"><?php echo I18n::__('analysis_label_sumweight') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('analysis_label_sumtotalpricenet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('analysis_label_avgmfa') ?></th>
                <th width="11%" class="number"><?php echo I18n::__('analysis_label_avgweight') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('analysis_label_avgdpricenet') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_suppliers as $_analysis_id => $_analysis): ?>
            <tr>
                <td><?php echo htmlspecialchars($_analysis->person->nickname) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('piggery', 0)) ?></td>
                <td class="number"><?php echo htmlspecialchars(number_format($_analysis->piggery * 100 / $record->piggery, 2, ',', '.')) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('itwpiggery', 0)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('sumweight', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('sumtotalpricenetitw', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('avgmfa', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('avgweight', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_analysis->decimal('avgpricenetitw', 3)) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize"><?php echo I18n::__('analysis_label_total') ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('piggery', 0)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('piggerypercentage', 2)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('itwpiggery', 0)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('sumweight', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('sumtotalpricenetitw', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgmfa', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgweight', 3)) ?></td>
                <td class="bt bb number"><?php echo htmlspecialchars($record->decimal('avgpricenetitw', 3)) ?></td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
                <td class="bt notemphasized number" colspan="4"><?php echo I18n::__('analysis_text_footer_overview') ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
