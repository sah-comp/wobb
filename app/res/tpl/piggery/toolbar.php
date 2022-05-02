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
			href="<?php echo Url::build("/piggery/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/piggery/add") ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
	<?php if (isset($record) && $record->getId()): ?>
    <li>
	    <a
			href="<?php echo Url::build("/piggery/csv/{$record->getId()}") ?>">
			<?php echo I18n::__('action_piggery_csv') ?>
		</a>
	</li>
	<li>
	    <a
			href="<?php echo Url::build("/piggery/pdf/{$record->getId()}") ?>">
			<?php echo I18n::__('action_piggery_print') ?>
		</a>
	</li>
	<?php endif ?>
</ul>
