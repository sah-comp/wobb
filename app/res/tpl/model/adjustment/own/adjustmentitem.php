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
<!-- adjustmentitem edit subform -->
<fieldset
    id="adjustment-<?php echo $record->getId() ?>-ownadjustmentitem-<?php echo $_adjustmentitem->getId() ?>">
    <legend class="verbose"><?php echo I18n::__('adjustment_legend_adjustmentitem') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/adjustment/detach/adjustmentitem/%d', $_adjustmentitem->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="adjustment-<?php echo $record->getId() ?>-ownadjustmentitem-<?php echo $_adjustmentitem->getId() ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/adjustment/attach/own/adjustmentitem/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="adjustment-<?php echo $record->getId() ?>-adjustmentitem-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div class="row">
        <div class="span1">&nbsp;</div>
        <div class="span3">
            <input type="hidden" name="dialog[ownAdjustmentitem][<?php echo $index ?>][type]" value="<?php echo $_adjustmentitem->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownAdjustmentitem][<?php echo $index ?>][id]" value="<?php echo $_adjustmentitem->getId() ?>" />
            <select
                id="adjustment-adjustmentitem-<?php echo $index ?>-person"
                name="dialog[ownAdjustmentitem][<?php echo $index ?>][person_id]">
                <option value=""><?php echo I18n::__('adjustmentitem_label_select') ?></option>
                <?php foreach (R::find('person', " enabled = 1 ORDER BY name") as $_person_id => $_person): ?>
                <option
                    value="<?php echo $_person->getId() ?>"
                    <?php echo ($_adjustmentitem->person_id == $_person->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_person->name) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="adjustment-adjustmentitem-<?php echo $index ?>-net"
                class="autowidth number"
                type="text"
                name="dialog[ownAdjustmentitem][<?php echo $index ?>][net]"
                value="<?php echo htmlspecialchars($_adjustmentitem->decimal('net', 2)) ?>" />
        </div>
        <div class="span2">
            <input
                id="adjustment-adjustmentitem-<?php echo $index ?>-vatvalue"
                class="autowidth number"
                type="text"
                readonly="readonly"
                name="dialog[ownAdjustmentitem][<?php echo $index ?>][vatvalue]"
                value="<?php echo ($_adjustmentitem->wasCalculated()) ? htmlspecialchars($_adjustmentitem->decimal('vatvalue', 2)) : I18n::__('adjustmentitem_not_yet_calculated')  ?>" />
        </div>
        <div class="span2">
            <input
                id="adjustment-adjustmentitem-<?php echo $index ?>-gros"
                class="autowidth number"
                type="text"
                readonly="readonly"
                name="dialog[ownAdjustmentitem][<?php echo $index ?>][gros]"
                value="<?php echo ($_adjustmentitem->wasCalculated()) ? htmlspecialchars($_adjustmentitem->decimal('gros', 2)) : I18n::__('adjustmentitem_not_yet_calculated')  ?>" />
        </div>
        <div class="span2">
            <input
                id="adjustment-adjustmentitem-<?php echo $index ?>-value"
                class="autowidth number"
                type="text"
                name="stash_invoice_name"
                readonly="readonly"
                value="<?php echo ($_adjustmentitem->wasBilled()) ? htmlspecialchars($_adjustmentitem->invoice()->name) : I18n::__('adjustmentitem_not_yet_billed')  ?>" />
        </div>
    </div>

</fieldset>
<!-- /margin edit subform -->