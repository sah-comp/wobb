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
        <div class="span3">
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][type]" value="<?php echo $_deliverer->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][id]" value="<?php echo $_deliverer->getId() ?>" />
			<input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][calcdate]" value="<?php echo $_deliverer->calcdate ?>" />
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
        <div class="span1">
            <input
                id="plan-deliverer-<?php echo $index ?>-piggery"
                class="autowidth number"
                type="text"
                name="dialog[ownDeliverer][<?php echo $index ?>][piggery]"
                value="<?php echo ($_deliverer->piggery) ?>" />
        </div>
        <div class="span1">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownDeliverer][<?php echo $index ?>][dprice]"
                value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('dprice', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                
            />
        </div>
        <div class="span3">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownDeliverer][<?php echo $index ?>][totalnet]"
                value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('totalnet', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                
            />
		</div>
        <div class="span1">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownDeliverer][<?php echo $index ?>][meanmfa]"
                value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('meanmfa', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                
            />
        </div>
        <div class="span1">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownDeliverer][<?php echo $index ?>][meanweight]"
                value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('meanweight', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                
            />
        </div>
        <div class="span1">
            <input
                type="text"
                class="number"
                readonly="readonly"
                name="dialog[ownDeliverer][<?php echo $index ?>][meandprice]"
                value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('meandprice', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                
            />
        </div>
    </div>

</fieldset>
<!-- /deliverer of plan edit subform -->