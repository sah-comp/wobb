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
<!-- margin edit subform -->
<fieldset
    id="pricing-<?php echo $record->getId() ?>-ownmargin-<?php echo $_margin->getId() ?>">
    <legend class="verbose"><?php echo I18n::__('pricing_legend_margin') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/pricing/detach/margin/%d', $_margin->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="pricing-<?php echo $record->getId() ?>-ownmargin-<?php echo $_margin->getId() ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/pricing/attach/own/margin/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="pricing-<?php echo $record->getId() ?>-margin-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <input type="hidden" name="dialog[ownMargin][<?php echo $index ?>][type]" value="<?php echo $_margin->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownMargin][<?php echo $index ?>][id]" value="<?php echo $_margin->getId() ?>" />
            <select
                id="pricing-margin-<?php echo $index ?>-kind"
                name="dialog[ownMargin][<?php echo $index ?>][kind]">
                <option value=""><?php echo I18n::__('margin_label_select') ?></option>
                <?php foreach (array('weight', 'mfa', 'mfasub') as $_kind): ?>
                <option
                    value="<?php echo $_kind ?>"
                    <?php echo ($_margin->kind == $_kind) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars(I18n::__('margin_label_'.$_kind)) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="pricing-margin-<?php echo $index ?>-lo"
                class="autowidth number"
                type="text"
                name="dialog[ownMargin][<?php echo $index ?>][lo]"
                value="<?php echo htmlspecialchars($_margin->decimal('lo')) ?>" />
        </div>
        <div class="span2">
            <input
                id="pricing-margin-<?php echo $index ?>-hi"
                class="autowidth number"
                type="text"
                name="dialog[ownMargin][<?php echo $index ?>][hi]"
                value="<?php echo htmlspecialchars($_margin->decimal('hi')) ?>" />
        </div>
        <div class="span2">
            <select
                id="pricing-margin-<?php echo $index ?>-op"
                name="dialog[ownMargin][<?php echo $index ?>][op]">
                <option value=""><?php echo I18n::__('margin_label_select') ?></option>
                <?php foreach (array('-', '+', '=') as $_op): ?>
                <option
                    value="<?php echo $_op ?>"
                    <?php echo ($_margin->op == $_op) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars(I18n::__('margin_label_'.$_op)) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span1">
            <input
                id="pricing-margin-<?php echo $index ?>-value"
                class="autowidth number"
                type="text"
                name="dialog[ownMargin][<?php echo $index ?>][value]"
                value="<?php echo htmlspecialchars($_margin->decimal('value')) ?>" />
        </div>
    </div>

</fieldset>
<!-- /margin edit subform -->