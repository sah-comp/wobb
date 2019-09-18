<?php echo Flight::textile(I18n::__('adjustmentitem_html_mail_avis', null, array(
    $record->adjustment->net,
    $record->adjustment->localizedDate('pubdate')
))) ?>
--<br />
<strong><?php echo htmlspecialchars( $record->adjustment->company->legalname ) ?></strong><br />
<a href="<?php echo $record->adjustment->company->website ?>"><?php echo htmlspecialchars( $record->adjustment->company->website ) ?></a><br />
<?php echo htmlspecialchars( $record->adjustment->company->street ) ?><br />
<?php echo htmlspecialchars( $record->adjustment->company->zip ) ?> 
<?php echo htmlspecialchars( $record->adjustment->company->city ) ?><br />
Telefon <?php echo htmlspecialchars( $record->adjustment->company->phone ) ?><br />
Fax <?php echo htmlspecialchars( $record->adjustment->company->fax ) ?><br />
Email <a href="mailto:<?php echo $record->adjustment->company->emailnoreply ?>"><?php echo htmlspecialchars( $record->adjustment->company->emailnoreply ) ?></a>
</p>