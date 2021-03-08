<?php
/**
 * Booking paper strips.
 */

/**
 * Number of strips per page.
 */
$_items_per_page = 2;
?>
<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 14pt;
        }
        .emphasize {
            font-weight: bold;
            font-size: 16pt;
        }
        .uberemphasize {
            font-size: 14pt;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
        }
        table.inbetween {
            margin-bottom: 15mm;
        }
        div.square {
            padding: 25mm 0 10mm 0;
            border: 0;
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
        }
        caption {
            font-weight: bold;
            padding-bottom: 3mm;
        }
        td {
            vertical-align: top;
        }
        th {
            text-align: left;
        }
        td.bt {
            border-top: 1px solid #000000;
        }
        td.br {
            border-right: 1px solid #000000;
        }
        td.bb {
            border-bottom: 1px solid #000000;
        }
        th.number,
        td.number {
            text-align: right;
        }
    </style>
</head>
<body>
<?php $_item = 0 ?>
<?php
    foreach ($records as $_id => $_record):
        $_item++;
?>
    <div class="square">

    <table width="100%" class="inbetween">
        <thead>
            <tr>
                <th width="30%"><?php echo I18n::__('invoice_label_person_account') ?></th>
                <th width="70%"><?php echo I18n::__('invoice_label_person_name') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($_record->getPersonAccount()) ?></td>
                <td><?php echo htmlspecialchars($_record->getPersonName()) ?></td>
            </tr>
        </tbody>
    </table>

    <table width="100%" class="inbetween">
        <thead>
            <tr>
                <th width="30%"><?php echo I18n::__('person_label_bic') ?></th>
                <th width="70%"><?php echo I18n::__('person_label_iban') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($_record->person->bic) ?></td>
                <td><?php echo htmlspecialchars($_record->person->iban) ?></td>
            </tr>
        </tbody>
    </table>

    <table width="100%" class="inbetween">
        <thead>
            <tr>
                <th width="30%"><?php echo I18n::__('invoice_label_name') ?></th>
                <th width="35%"><?php echo I18n::__('invoice_label_dateofslaughter') ?></th>
                <th class="number" width="35%"><?php echo I18n::__('invoice_label_totalgros') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($_record->name) ?></td>
                <td><?php echo htmlspecialchars($_record->localizedDate('dateofslaughter')) ?></td>
                <td class="number emphasize"><?php echo htmlspecialchars($_record->decimal('totalgros', 2)) ?></td>
            </tr>
        </tbody>
    </table>

    </div>
<?php
        if ($_item == $_items_per_page):
            $_item = 0;
            ?>
            <!--mpdf
            <pagebreak />
            mpdf-->
            <?php
        endif;
    endforeach;
?>
</body>
</html>
