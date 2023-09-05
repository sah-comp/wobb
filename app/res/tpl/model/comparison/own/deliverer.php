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
<!-- deliverer of comparison edit subform -->
<fieldset
    id="comparison-<?php echo $record->getId() ?>-owndeliverer-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('comparison_legend_deliverer') ?></legend>
    <a
        href="<?php echo Url::build(sprintf('/admin/comparison/detach/deliverer/%d', $_deliverer->getId())) ?>"
        class="ir detach"
        title="<?php echo I18n::__('scaffold_detach') ?>"
        data-target="comparison-<?php echo $record->getId() ?>-owndeliverer-<?php echo $index ?>">
            <?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
        href="<?php echo Url::build(sprintf('/admin/comparison/attach/own/deliverer/%d', $record->getId())) ?>"
        class="ir attach"
        title="<?php echo I18n::__('scaffold_attach') ?>"
        data-target="comparison-<?php echo $record->getId() ?>-deliverer-container">
            <?php echo I18n::__('scaffold_attach') ?>
    </a>
    <div class="row">
        <div class="span1">&nbsp;</div>
        <div class="span2">&nbsp;</div>
        <div class="span1">
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][type]" value="<?php echo $_deliverer->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][id]" value="<?php echo $_deliverer->getId() ?>" />
            <input type="hidden" name="dialog[ownDeliverer][<?php echo $index ?>][calcdate]" value="<?php echo $_deliverer->calcdate ?>" />
            <select
                id="comparison-deliverer-<?php echo $index ?>-person"
                name="dialog[ownDeliverer][<?php echo $index ?>][person_id]">
                <option value=""><?php echo I18n::__('deliverer_label_select') ?></option>
                <?php foreach (R::find('person', " enabled = 1 ORDER BY name") as $_person_id => $_person) : ?>
                <option
                    value="<?php echo $_person->getId() ?>"
                    <?php echo ($_deliverer->person_id == $_person->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_person->nickname . ' – ' . $_person->name) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span4">&nbsp;</div>
        <div class="span1">
            <input
                type="text"
                class="number autowidth"
                name="dialog[ownDeliverer][<?php echo $index ?>][diff]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_deliverer->decimal('diff', 2)) ?>" />
        </div>
        <div class="span2">
            <input
                type="text"
                class="number autowidth"
                name="dialog[ownDeliverer][<?php echo $index ?>][totalnet]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_deliverer->decimal('totalnet', 2)) ?>" />
        </div>
        <div class="span1">
            <input
                type="text"
                class="number autowidth"
                name="dialog[ownDeliverer][<?php echo $index ?>][avgprice]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($_deliverer->decimal('avgprice', 3)) ?>" />
        </div>
    </div>

</fieldset>
<!-- /deliverer of comparison edit subform -->