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
<!-- end of var edit form -->