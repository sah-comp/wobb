<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<ul class="panel-navigation">
	<li>
		<a
			href="<?php echo Url::build("/booking/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/booking/pdflist") ?>"
			accesskey="+">
			<?php echo I18n::__('booking_action_pdflist') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/booking/pdfbooking") ?>"
			accesskey="+">
			<?php echo I18n::__('booking_action_pdfbooking') ?>
		</a>
	</li>
</ul>
