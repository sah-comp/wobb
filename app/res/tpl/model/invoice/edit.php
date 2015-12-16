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
<!-- invoice edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('invoice_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('fy')) ? 'error' : ''; ?>">
        <label
            for="invoice-fy">
            <?php echo I18n::__('action_label_fy') ?>
        </label>
        <input
            id="invoice-fy"
            type="text"
            name="dialog[fy]"
            value="<?php echo htmlspecialchars($record->fy) ?>"
            required="required"
            readonly="readonly" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="invoice-name">
            <?php echo I18n::__('action_label_invoice') ?>
        </label>
        <input
            id="invoice-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required"
            readonly="readonly" />
    </div>
</fieldset>
<!-- end of invoice edit form -->