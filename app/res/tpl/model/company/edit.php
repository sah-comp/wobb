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
<!-- company edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('company_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="company-name">
            <?php echo I18n::__('company_label_name') ?>
        </label>
        <input
            id="company-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('active')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[active]"
            value="0" />
        <input
            id="company-active"
            type="checkbox"
            name="dialog[active]"
            <?php echo ($record->active) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="company-active"
            class="cb">
            <?php echo I18n::__('company_label_active') ?>
        </label>
    </div>
    <div class="row <?php echo ($record->hasError('buyer')) ? 'error' : ''; ?>">
        <label
            for="company-buyer">
            <?php echo I18n::__('company_label_buyer') ?>
        </label>
        <input
            id="company-buyer"
            type="text"
            name="dialog[buyer]"
            value="<?php echo htmlspecialchars($record->buyer) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('nextbillingnumber')) ? 'error' : ''; ?>">
        <label
            for="company-nextbillingnumber">
            <?php echo I18n::__('company_label_nextbillingnumber') ?>
        </label>
        <input
            id="company-nextbillingnumber"
            type="number"
            step="1"
            name="dialog[nextbillingnumber]"
            value="<?php echo htmlspecialchars($record->nextbillingnumber) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of company edit form -->