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
			href="<?php echo Url::build("/lab/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/lab/add") ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
	<?php if (isset($record) && is_a($record, 'RedBean_OODBBean') && $record->getId()): ?>
	<li>
	    <a
			href="<?php echo Url::build("/lab/pdf/{$record->getId()}") ?>">
			<?php echo I18n::__('action_lab_print') ?>
		</a>
	</li>
	<?php endif ?>
</ul>
