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
<!-- deliverer of plan edit subform -->
<fieldset
    id="plan-<?php echo $record->getId() ?>-owndeliverer-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('plan_legend_deliverer') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/plan/detach/deliverer/%d', $_deliverer->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="plan-<?php echo $record->getId() ?>-owndeliverer-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/plan/attach/own/deliverer/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="plan-<?php echo $record->getId() ?>-deliverer-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div class="row">
        <div class="span1">&nbsp;</div>
        <div class="span2">
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][type]" value="<?php echo $_deliverer->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][id]" value="<?php echo $_deliverer->getId() ?>" />
            <select
                id="plan-deliverer-<?php echo $index ?>-person"
                name="dialog[ownDeliverer][<?php echo $index ?>][person_id]">
                <option value=""><?php echo I18n::__('deliverer_label_select') ?></option>
                <?php foreach (R::find('person', " enabled = 1 ORDER BY name") as $_person_id => $_person): ?>
                <option
                    value="<?php echo $_person->getId() ?>"
                    <?php echo ($_deliverer->person_id == $_person->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_person->nickname . ' – ' . $_person->name) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
			Preismaske
        </div>
        <div class="span7">
            <input
                id="plan-deliverer-<?php echo $index ?>-piggery"
                class="autowidth number"
                type="text"
                name="dialog[ownDeliverer][<?php echo $index ?>][piggery]"
                value="<?php echo ($_deliverer->piggery) ?>" />
        </div>
    </div>

</fieldset>
<!-- /deliverer of plan edit subform -->