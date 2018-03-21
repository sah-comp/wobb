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
            margin-bottom: 10mm;
        }
        div.square {
            padding: 5mm 0;
            border-top: 0.1mm solid dashed;
            border-bottom: 0.1mm solid dashed;
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
            border-top: 0.1mm solid #000000;
        }
        td.br {
            border-right: 0.1mm solid #000000;
        }
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

    <table width="100%">
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
        if ($_item == 3):
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
