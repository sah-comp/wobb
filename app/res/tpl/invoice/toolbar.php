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
			href="<?php echo Url::build("/invoice/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/invoice/pdf") ?>"
			accesskey="+">
			<?php echo I18n::__('invoice_action_pdf') ?>
		</a>
	</li>
</ul>
