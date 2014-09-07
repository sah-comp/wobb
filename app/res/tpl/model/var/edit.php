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
</fieldset>
<!-- end of var edit form -->