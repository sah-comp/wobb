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
    mpdf-->
    
    <table width="100%">
        <tr>
            <td colspan="3" class="dinky centered"><h1><?php echo $record->name ?></h1></td>
        </tr>
        <tr>
            <td width="33.3%" class="br" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_weightmargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->withCondition(" kind='weight' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
            <td width="33.3%" class="br" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfamargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->withCondition(" kind='mfa' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
            <td width="33.3%" class="" style="vertical-align: top;">
                <table width="100%">
                    <thead>
                        <tr>
                            <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfasubmargin') ?></td>
                        </tr>
                        <tr>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                            <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                            <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                        </tr>
                        <tbody>
                    <?php foreach ($record->withCondition(" kind='mfasub' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                            <tr>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                                <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                                <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                            </tr>
                    <?php endforeach ?>
                        </tbody>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
