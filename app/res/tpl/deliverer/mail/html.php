<?php echo Flight::textile(I18n::__('deliverer_text_mail_invoice', null, array(
    $record->invoice->name,
    $record->invoice->localizedDate('bookingdate'),
    $record->invoice->localizedDate('dateofslaughter')
))) ?>
--<br />
<strong><?php echo htmlspecialchars($record->invoice->company->legalname) ?></strong><br />
<a href="<?php echo $record->invoice->company->website ?>"><?php echo htmlspecialchars($record->invoice->company->website) ?></a><br />
<?php echo htmlspecialchars($record->invoice->company->street) ?><br />
<?php echo htmlspecialchars($record->invoice->company->zip) ?>
<?php echo htmlspecialchars($record->invoice->company->city) ?><br />
Telefon <?php echo htmlspecialchars($record->invoice->company->phone) ?><br />
Fax <?php echo htmlspecialchars($record->invoice->company->fax) ?><br />
Email <a href="mailto:<?php echo $record->invoice->company->emailnoreply ?>"><?php echo htmlspecialchars($record->invoice->company->emailnoreply) ?></a>
</p>
