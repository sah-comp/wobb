<?php
/**
 * Renders a decimal value nicely.
 *
 * @param string $value
 * @param int $decimals defaults to 2
 * @param string $decimal_point defaults to '.'
 * @param string $thousands_separator defaults to ','
 * @return string
 */    
function myDecimal($value, $decimals = 2, $decimal_point = ',', $thousands_separator = '.')
{
    if ( ! $value ) return '';
    return number_format($value, $decimals, $decimal_point, $thousands_separator);
}
?>
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
        td {
            vertical-align: top;
            white-space: nowrap;
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
                <td width="40%" style="text-align: right;"><?php echo I18n::__('booking_text_header', null, array($fy, $lo, $hi)) ?></td>
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
    
    <div style="height: 5mm;"></div>
    
    <table class="invoice" width="100%">
        <thead>
            <tr>
                <th width="5%"><?php echo I18n::__('invoice_label_name') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_label_dateofslaughter_list') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_label_person_account') ?></th>
                <th width="5%"><?php echo I18n::__('invoice_label_person_name') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_totalnet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_bonusnet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_costnet') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_totalnetnormal') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_totalnetfarmer') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_totalnetother') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_vatvalue') ?></th>
                <th width="10%" class="number"><?php echo I18n::__('invoice_label_totalgros') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $_id => $_record): ?>
            <tr>
                <td><?php echo htmlspecialchars($_record->name) ?></td>
                <td><?php echo htmlspecialchars($_record->localizedDate('dateofslaughter')) ?></td>
                <td><?php echo htmlspecialchars($_record->getPersonAccount()) ?></td>
                <td><?php echo htmlspecialchars($_record->getPersonNickname()) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('totalnet', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('bonusnet', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('costnet', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('totalnetnormal', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('totalnetfarmer', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('totalnetother', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('vatvalue', 2)) ?></td>
                <td class="number"><?php echo htmlspecialchars($_record->decimal('totalgros', 2)) ?></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <td class="bt bb emphasize"><?php echo I18n::__('invoice_label_total') ?></td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bt bb">&nbsp;</td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['totalnet'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['bonusnet'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['costnet'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['totalnetnormal'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['totalnetfarmer'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['totalnetother'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['vatvalue'])) ?></td>
                <td class="bb bt number emphasize"><?php echo htmlspecialchars(myDecimal($totals['totalgros'])) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
