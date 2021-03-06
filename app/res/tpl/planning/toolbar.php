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
			href="<?php echo Url::build("/planning/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/planning/add") ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
	<?php if (isset($record) && $record->getId()): ?>
	<li>
	    <a
			href="<?php echo Url::build("/planning/pdf/{$record->getId()}/?layout=prices") ?>">
			<?php echo I18n::__('action_planning_print_prices') ?>
		</a>
	</li>
	<li>
	    <a
			href="<?php echo Url::build("/planning/pdf/{$record->getId()}") ?>">
			<?php echo I18n::__('action_planning_print') ?>
		</a>
	</li>
	<?php endif ?>
</ul>
