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
			href="<?php echo Url::build("/statistic/index") ?>"
			accesskey="i">
			<?php echo I18n::__('action_list_nav') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/statistic/add") ?>"
			accesskey="+">
			<?php echo I18n::__('action_add_nav') ?>
		</a>
	</li>
	<?php if (isset($record) && is_a($record, 'RedBean_OODBBean') && $record->getId()): ?>
	<li>
        <a
            href="<?php echo Url::build(sprintf("/statistic/download/%d", $record->getId())) ?>">
            <?php echo I18n::__('lanuv_href_lanuv_download') ?>
        </a>
	</li>
	<li>
        <a
            href="<?php echo Url::build(sprintf("/statistic/mail/%d", $record->getId())) ?>"
            class="mail <?php echo $record->wasSent() ?>">
            <?php echo I18n::__('lanuv_href_lanuv_report') ?>
        </a>
	</li>
	<li>
        <a
            href="<?php echo Url::build(sprintf("/statistic/weekascsv/%d", $record->getId())) ?>"
            class="">
            <?php echo I18n::__('lanuv_href_export_csv') ?>
        </a>
	</li>
	<li>
	    <a
			href="<?php echo Url::build("/statistic/pdf/{$record->getId()}") ?>">
			<?php echo I18n::__('action_lanuv_print') ?>
		</a>
	</li>

	<?php endif ?>
</ul>
