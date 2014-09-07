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
			href="<?php echo Url::build("/purchase/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/purchase/add") ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
</ul>
