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
	<?php if (isset($record) && $record->getId() && $record->hasIqagrar()): ?>
	<li>
        <a
            href="<?php echo Url::build(sprintf("/purchase/iqagrar/%d", $record->getId())) ?>"
            class="mail <?php echo $record->wasIqagrarSent() ?>">
            <?php echo I18n::__('action_purchase_iqagrar') ?>
        </a>
	</li>
	<?php endif ?>
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
