<htmlpageheader name="tkheader-firstpage" style="display: none;">
	<table width="100%">
		<tr>
			<td style="vertical-align: top; width: 120px;">
				<img src="img/<?php echo Flight::setting()->logo ?>" width="<?php echo Flight::setting()->logowidth ?>px" height="<?php echo Flight::setting()->logoheight ?>px" alt="" />
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
					<tr>
						<td class="value"><?php echo Flight::textile(I18n::__('invoice_column_two')); ?></td>
					</tr>
				</table>
			</td>
			<td style="vertical-align: top;">
				<table class="pageheader" width="100%">
					<tr>
						<td class="value"><?php echo Flight::textile(I18n::__('invoice_column_three')); ?></td>
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
						<td class="label"><?php echo I18n::__('company_label_bankname') ?></td>
						<td class="value"><?php echo htmlspecialchars($record->invoice->company->bankname) ?></td>
					</tr>
					<tr>
						<td class="label"><?php echo I18n::__('company_label_bic') ?></td>
						<td class="value"><?php echo htmlspecialchars($record->invoice->company->bic) ?></td>
					</tr>
					<tr>
						<td class="label"><?php echo I18n::__('company_label_iban') ?></td>
						<td class="value"><?php echo htmlspecialchars($record->invoice->company->iban) ?></td>
					</tr>
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
		</tr>
	</table>
</htmlpageheader>