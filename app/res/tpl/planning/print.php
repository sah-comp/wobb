<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 10pt;
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('planning_text_header', null, [$pubdate]) ?></td>
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
    
    <table class="planning" width="100%">
        <caption>
            <?php echo I18n::__('planning_caption_total', null, [$pubdate, $record->decimal('baseprice', 3)]) ?>
        </caption>
        <thead>
            <tr>
                <th width="30%"><?php echo I18n::__('plan_deliverer_label_person') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('plan_deliverer_label_piggery') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('plan_deliverer_label_baseprice') ?></th>
                <th width="20%" class="number"><?php echo I18n::__('plan_deliverer_label_totalnet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('plan_deliverer_label_mfa') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('plan_deliverer_label_weight') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('plan_deliverer_label_price') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($record->with(" ORDER BY id ")->ownDeliverer as $_id => $_deliverer): ?>
            <tr>
                <td style="white-space: nowrap;"><?php echo htmlspecialchars($_deliverer->person->nickname . ' ' . $_deliverer->person->name) ?></td>
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('piggery', 0)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?></td>				
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('totalnet', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('meanmfa', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('meanweight', 3)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_deliverer->decimal('meandprice', 3)) ?></td>
            </tr>
        <?php endforeach ?>
			<tr>
                <td class="bt bb emphasize"><?php echo I18n::__('plan_label_total') ?></td>
				<td class="bt bb number"><?php echo htmlspecialchars($record->decimal('piggery', 0)) ?></td>
				<td class="bt bb"></td>
				<td class="bt bb number"><?php echo htmlspecialchars($record->decimal('totalnet', 3)) ?></td>
				<td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanmfa', 3)) ?></td>
				<td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meanweight', 3)) ?></td>
				<td class="bt bb number"><?php echo htmlspecialchars($record->decimal('meandprice', 3)) ?></td>
			</tr>
            <tr>
                <td class="bt gap notemphasized number" colspan="7"><?php echo I18n::__('planning_text_footer', null, [$record->period]) ?></td>
            </tr>
        </tbody>
    </table>
        
</body>
</html>
