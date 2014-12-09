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
<!-- damage edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('damage_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('supplier')) ? 'error' : ''; ?>">
        <label
            for="damage-supplier">
            <?php echo I18n::__('damage_label_supplier') ?>
        </label>
        <input
            id="damage-supplier"
            type="text"
            name="dialog[supplier]"
            value="<?php echo htmlspecialchars($record->supplier) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="damage-name">
            <?php echo I18n::__('damage_label_name') ?>
        </label>
        <input
            id="damage-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('desc')) ? 'error' : ''; ?>">
        <label
            for="damage-desc">
            <?php echo I18n::__('damage_label_desc') ?>
        </label>
        <input
            id="damage-desc"
            type="text"
            name="dialog[desc]"
            value="<?php echo htmlspecialchars($record->desc) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('condition')) ? 'error' : ''; ?>">
        <label
            for="damage-<?php echo $record->getId() ?>-label">
            <?php echo I18n::__('damage_label_condition') ?>
        </label>
        <select
            id="damage-<?php echo $record->getId() ?>-condition"
            name="dialog[condition]">
            <?php foreach ($record->getConditions() as $_condition): ?>
            <option
                value="<?php echo $_condition ?>"
                <?php echo ($record->condition == $_condition) ? 'selected="selected"' : '' ?>>
                <?php echo I18n::__('damage_condition_'.$_condition) ?>
            </option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('sprice')) ? 'error' : ''; ?>">
        <label
            for="damage-dprice">
            <?php echo I18n::__('damage_label_sprice') ?>
        </label>
        <input
            type="text"
            class="number"
            name="dialog[sprice]"
            value="<?php echo htmlspecialchars($record->decimal('sprice', 3)) ?>"
            placeholder="<?php echo htmlspecialchars($record->decimal('sprice', 3)) ?>"
        />
        <p class="info"><?php echo I18n::__('damage_info_sprice') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('dprice')) ? 'error' : ''; ?>">
        <label
            for="damage-dprice">
            <?php echo I18n::__('damage_label_dprice') ?>
        </label>
        <input
            type="text"
            class="number"
            name="dialog[dprice]"
            value="<?php echo htmlspecialchars($record->decimal('dprice', 3)) ?>"
            placeholder="<?php echo htmlspecialchars($record->decimal('dprice', 3)) ?>"
        />
        <p class="info"><?php echo I18n::__('damage_info_dprice') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('enabled')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[enabled]"
            value="0" />
        <input
            id="damage-enabled"
            type="checkbox"
            name="dialog[enabled]"
            <?php echo ($record->enabled) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="damage-enabled"
            class="cb">
            <?php echo I18n::__('damage_label_enabled') ?>
        </label>
    </div>
</fieldset>
<!-- end of damage edit form -->