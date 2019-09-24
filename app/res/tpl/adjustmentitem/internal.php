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
		.desc,
		.subject {
			font-size: 13pt;
		}
		.subject {
			font-weight: bold;
		}
        .dinky {
            font-size: 8pt;
        }
        .moredinky {
            font-size: 6pt;
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
    <htmlpageheader name="tkheader-firstpage" style="display: none;">
        <table width="100%">
            <tr>
                <td style="vertical-align: top; width: 120px;">
                    <img src="/img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
                        <tr>
                            <td class="value">
                                <?php echo htmlspecialchars($record->invoice->company->legalname) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="value">
                            <?php echo htmlspecialchars($record->invoice->company->street) ?></td>
                        </tr>
                        <tr>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->zip) ?> <?php echo htmlspecialchars($record->invoice->company->city) ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_phone') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->phone) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_fax') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->fax) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_email') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->email) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_website') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->website) ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_taxoffice') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->taxoffice) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_taxid') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->taxid) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_vatid') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->vatid) ?></td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table class="pageheader" width="100%">
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_bankname') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankname) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_bankcode') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankcode) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_bankaccount') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->bankaccount) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_bic') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->bic) ?></td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo I18n::__('company_label_iban') ?></td>
                            <td class="value"><?php echo htmlspecialchars($record->invoice->company->iban) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpageheader name="tkheader" style="display: none;">
        <table width="100%">
            <tr>
                <td width="60%" style="text-align: left;"><?php echo htmlspecialchars($record->invoice->company->legalname) ?></td>
                <td width="40%" style="text-align: right;"><?php echo I18n::__('invoice_internal_text_voucher', null, array($record->invoice->name, $bookingdate)) ?></td>
            </tr>
        </table>
    </htmlpageheader>
    <htmlpagefooter name="tkfooter" style="display: none;">
        <div style="border-top: 0.1mm solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm;">
            <?php echo I18n::__('invoice_text_page') ?> {PAGENO} <?php echo I18n::__('invoice_text_of') ?> {nb}
        </div>
    </htmlpagefooter>
    <sethtmlpageheader name="tkheader-firstpage" value="on" show-this-page="1" />
    <sethtmlpageheader name="tkheader" value="on" />
    <sethtmlpagefooter name="tkfooter" value="on" />
    mpdf-->
    
    <div style="height: 25mm;"></div>
    <table width="100%">
        <tr>
            <td style="width: 95mm; vertical-align: top;">
                <div class="senderline">
                    <?php echo htmlspecialchars($record->invoice->company->getSenderline()) ?>
                    <br /><br />
                </div>
                <div class="name">
                    <?php echo htmlspecialchars($record->person->name) ?>
                </div>
                <div class="postal">
                    <p>
                        <?php echo nl2br(htmlspecialchars($record->person->getAddress('billing')->getFormattedAddress())) ?>
                    </p>
                </div>
            </td>
            <td style="width: 65mm; vertical-align: top;">
                <table class="info" width="100%">
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_person') ?></label>
                        <td class="value emphasize"><?php echo $record->person->account ?></label>
                    </tr>
                    <tr>
                        <td class="label"><?php echo I18n::__('invoice_internal_label_taxid') ?></label>
                        <td class="value"><?php echo $record->person->taxid ?></label>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <div style="height: 18mm;"></div>  
    <table width="100%">
		<tr>
			<td class="subject" style="vertical-align: top;">
				<?php echo Flight::textile(I18n::__('adjustmentitem_bookingslot_text')) ?>
			</td>
		</tr>
	</table>
    <div style="height: 18mm;"></div>
	<table width="100%">
		<tr>
			<td class="desc" style="vertical-align: top;">
				<?php echo Flight::textile(I18n::__('adjustmentitem_wawi_text', null, array($bookingslot))) ?>
			</td>
		</tr>
	</table>
    <div style="height: 18mm;"></div>
	<table width="100%">
		<tr>
			<td>
				<table width="100%">
					<tr>
						<th><?php echo Flight::textile(I18n::__('adjustmentitem_label_del_inv')) ?></th>
						<th><?php echo Flight::textile(I18n::__('adjustmentitem_label_del_date')) ?></th>
						<th><?php echo Flight::textile(I18n::__('adjustmentitem_label_invoice')) ?></th>						
						<th class="number"><?php echo Flight::textile(I18n::__('adjustmentitem_label_gros')) ?></th>
					</tr>
					<?php foreach ($records as $_id => $_record): ?>
					<tr>
						<td><?php echo $_record->delinv ?></td>
						<td><?php echo $_record->localizedDate('deldate') ?></td>
						<td><?php echo $_record->invoice->name ?></td>
						<td class="number"><?php echo $_record->decimal('gros', 2) ?></td>
					</tr>
					<?php endforeach ?>
					<tr>
						<td colspan="3" class="bt bb number"><?php echo Flight::textile(I18n::__('adjustment_label_total')) ?></td>
						<td class="bt bb emphasize number"><?php echo number_format($total, 2, ',', '.') ?></td>
					</tr>
				</table>
			</td>
		</tr>
    </table>
</body>
</html>
