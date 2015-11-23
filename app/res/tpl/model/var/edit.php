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
<!-- var edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('var_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="var-name">
            <?php echo I18n::__('var_label_name') ?>
        </label>
        <input
            id="var-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('kind')) ? 'error' : ''; ?>">
        <label
            for="var-kind">
            <?php echo I18n::__('var_label_kind') ?>
        </label>
        <select
            id="var-kind"
            name="dialog[kind]">
            <?php foreach ($record->getKinds() as $_kind): ?>
            <option
                value="<?php echo $_kind ?>"
                <?php echo ($record->kind == $_kind) ? 'selected="selected"' : '' ?>>
                <?php echo I18n::__('var_kind_'.$_kind) ?>
            </option>
            <?php endforeach ?>
        </select>
        <p class="info"><?php echo I18n::__('var_info_kind') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('supplier')) ? 'error' : ''; ?>">
        <label
            for="var-supplier">
            <?php echo I18n::__('var_label_supplier') ?>
        </label>
        <input
            id="var-supplier"
            type="text"
            name="dialog[supplier]"
            value="<?php echo htmlspecialchars($record->supplier) ?>" />
        <p class="info"><?php echo I18n::__('var_info_supplier') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('note')) ? 'error' : ''; ?>">
        <label
            for="var-note">
            <?php echo I18n::__('var_label_note') ?>
        </label>
        <textarea
            id="var-note"
            name="dialog[note]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->note) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('condition')) ? 'error' : ''; ?>">
        <label
            for="var-condition">
            <?php echo I18n::__('var_label_condition') ?>
        </label>
        <select
            id="var-condition"
            name="dialog[condition]">
            <?php foreach ($record->getConditions() as $_condition): ?>
            <option
                value="<?php echo $_condition ?>"
                <?php echo ($record->condition == $_condition) ? 'selected="selected"' : '' ?>>
                <?php echo I18n::__('var_condition_'.$_condition) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('sprice')) ? 'error' : ''; ?>">
        <label
            for="var-sprice">
            <?php echo I18n::__('var_label_sprice') ?>
        </label>
        <input
            id="var-sprice"
            type="text"
            name="dialog[sprice]"
            value="<?php echo htmlspecialchars($record->decimal('sprice', 3)) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('dprice')) ? 'error' : ''; ?>">
        <label
            for="var-dprice">
            <?php echo I18n::__('var_label_dprice') ?>
        </label>
        <input
            id="var-dprice"
            type="text"
            name="dialog[dprice]"
            value="<?php echo htmlspecialchars($record->decimal('dprice', 3)) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('doesnotaffectlanuv')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[doesnotaffectlanuv]"
            value="0" />
        <input
            id="var-doesnotaffectlanuv"
            type="checkbox"
            name="dialog[doesnotaffectlanuv]"
            <?php echo ($record->doesnotaffectlanuv) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="var-doesnotaffectlanuv"
            class="cb">
            <?php echo I18n::__('var_label_doesnotaffectlanuv') ?>
        </label>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'var-tabs',
        'tabs' => array(
            'var-cost' => I18n::__('var_cost_tab')
        ),
        'default_tab' => 'var-cost'
    )) ?>
    <fieldset
        id="var-cost"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('var_legend_cost') ?></legend>
        <div
            id="var-<?php echo $record->getId() ?>-cost-container"
            class="container attachable detachable sortable">
            <?php if (count($record->ownCost) == 0) $record->ownCost[] = R::dispense('cost') ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownCost as $_cost_id => $_cost): ?>
            <?php $index++ ?>
            <?php Flight::render('model/var/own/cost', array(
                'record' => $record,
                '_cost' => $_cost,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
</div>
<!-- end of var edit form -->