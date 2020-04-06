<?php echo Flight::textile(I18n::__('lanuv_mail_html', null, [$record->weekOfYear(), $record->localizedDate('startdate')])) ?>
--<br />
<strong><?php echo htmlspecialchars( $record->company->legalname ) ?></strong><br />
<a href="<?php echo $record->company->website ?>"><?php echo htmlspecialchars( $record->company->website ) ?></a><br />
<?php echo htmlspecialchars( $record->company->street ) ?><br />
<?php echo htmlspecialchars( $record->company->zip ) ?> 
<?php echo htmlspecialchars( $record->company->city ) ?><br />
Telefon <?php echo htmlspecialchars( $record->company->phone ) ?><br />
Fax <?php echo htmlspecialchars( $record->company->fax ) ?><br />
Email <a href="mailto:<?php echo $record->company->emailnoreply ?>"><?php echo htmlspecialchars( $record->company->emailnoreply ) ?></a>
</p>