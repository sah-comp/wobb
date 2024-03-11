<?php echo I18n::__('deliverer_text_mail_invoice', null, array(
    $record->invoice->name,
    $record->invoice->localizedDate('bookingdate'),
    $record->invoice->localizedDate('dateofslaughter')
)) ?>
--
<?php echo htmlspecialchars($record->invoice->company->legalname) ?>
<?php echo htmlspecialchars($record->invoice->company->website) ?>
<?php echo htmlspecialchars($record->invoice->company->street) ?>
<?php echo htmlspecialchars($record->invoice->company->zip) ?> <?php echo htmlspecialchars($record->invoice->company->city) ?>
Telefon <?php echo htmlspecialchars($record->invoice->company->phone) ?>
Fax <?php echo htmlspecialchars($record->invoice->company->fax) ?>
Email <?php echo htmlspecialchars($record->invoice->company->email) ?>
