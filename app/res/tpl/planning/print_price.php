<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 10pt;
        }
        table.planning {
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
                <th width="10%" class="text">&nbsp;</th>
                <th width="40%" class="text"><?php echo I18n::__('plan_deliverer_label_desc') ?></th>
            </tr>
        </thead>
        <tbody>
		<?php $_deliverers = $record->getDeliverers() ?>
        <?php foreach ($_deliverers as $_id => $_deliverer): ?>
            <tr>
                <td class="bb" style="white-space: nowrap;"><?php echo htmlspecialchars($_deliverer->person->nickname . ' ' . $_deliverer->person->name) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($_deliverer->decimal('piggery', 0)) ?></td>
                <td class="bb number"><?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?></td>
                <td class="bb text">&nbsp;</td>
                <td class="bb text"><?php echo htmlspecialchars($_deliverer->desc) ?></td>
            </tr>
        <?php endforeach ?>
			<tr>
		        <td class="bt bb emphasize"><?php echo I18n::__('plan_label_total') ?></td>
				<td class="bt bb number emphasize"><?php echo htmlspecialchars($record->decimal('piggery', 0)) ?></td>
				<td class="bt bb" colspan="3"></td>
			</tr>
			<tr>
				<td class="gap" colspan="5"></td>
			</tr>
			<tr>
				<td class="text" colspan="2"><?php echo I18n::__('plan_label_baseprice') ?></td>
				<td class="number"><?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?></td>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td class="text" colspan="2"><?php echo I18n::__('plan_label_nextweekprice') ?></td>
				<td class="number"><?php echo htmlspecialchars($record->decimal('nextweekprice', 3)) ?></td>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td class="text" colspan="2"><?php echo I18n::__('plan_label_sowprice') ?></td>
				<td class="number"><?php echo htmlspecialchars($record->decimal('sowprice', 3)) ?></td>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td class="text" colspan="2"><?php echo I18n::__('plan_label_damageprice') ?></td>
				<td class="number"><?php echo htmlspecialchars($record->decimal('damageprice', 3)) ?></td>
				<td colspan="2"></td>
			</tr>
        </tbody>
    </table>

</body>
</html>
