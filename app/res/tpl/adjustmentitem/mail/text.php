<?php echo I18n::__('adjustmentitem_text_mail_avis', null, array(
	$record->adjustment->net,
	$record->adjustment->localizedDate('pubdate')
)) ?>
--
<?php echo htmlspecialchars( $record->adjustment->company->legalname ) ?>
<?php echo htmlspecialchars( $record->adjustment->company->website ) ?>
<?php echo htmlspecialchars( $record->adjustment->company->street ) ?>
<?php echo htmlspecialchars( $record->adjustment->company->zip ) ?> <?php echo htmlspecialchars( $record->adjustment->company->city ) ?>
Telefon <?php echo htmlspecialchars( $record->adjustment->company->phone ) ?>
Fax <?php echo htmlspecialchars( $record->adjustment->company->fax ) ?>
Email <?php echo htmlspecialchars( $record->adjustment->company->emailnoreply ) ?>
