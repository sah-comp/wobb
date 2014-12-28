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
<!-- vat edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('vat_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="vat-name">
            <?php echo I18n::__('vat_label_name') ?>
        </label>
        <input
            id="vat-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('value')) ? 'error' : ''; ?>">
        <label
            for="vat-value">
            <?php echo I18n::__('vat_label_value') ?>
        </label>
        <input
            id="vat-value"
            class="number"
            type="text"
            name="dialog[value]"
            value="<?php echo htmlspecialchars($record->decimal('value', 3)) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of vat edit form -->