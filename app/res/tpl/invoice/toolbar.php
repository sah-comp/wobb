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
    <?php if ($hasRecords): ?>
    <li>
        <a
            href="<?php echo Url::build('/invoice/mail/') ?>"
            class="mail">
            <?php echo I18n::__('taxconsultant_href_mail') ?>
        </a>
    </li>
    <li>
		<a
			href="<?php echo Url::build("/invoice/csv") ?>">
			<?php echo I18n::__('invoice_action_csv') ?>
		</a>
	</li>
	<li>
		<a
			href="<?php echo Url::build("/invoice/pdf") ?>"
			accesskey="+">
			<?php echo I18n::__('invoice_action_pdf') ?>
		</a>
	</li>
    <?php endif; ?>
</ul>
