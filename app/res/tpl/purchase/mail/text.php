<?php echo I18n::__('iqagrar_mail_text', null, [$record->localizedDate('pubdate')]) ?>
--
<?php echo htmlspecialchars( $record->company->legalname ) ?>
<?php echo htmlspecialchars( $record->company->website ) ?>
<?php echo htmlspecialchars( $record->company->street ) ?>
<?php echo htmlspecialchars( $record->company->zip ) ?> <?php echo htmlspecialchars( $record->company->city ) ?>
Telefon <?php echo htmlspecialchars( $record->company->phone ) ?>
Fax <?php echo htmlspecialchars( $record->company->fax ) ?>
Email <?php echo htmlspecialchars( $record->company->emailnoreply ) ?>
