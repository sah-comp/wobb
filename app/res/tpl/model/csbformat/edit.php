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
<!-- csbformat edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('csbformat_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="csbformat-name">
            <?php echo I18n::__('csbformat_label_name') ?>
        </label>
        <input
            id="csbformat-name"
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
            id="csbformat-active"
            type="checkbox"
            name="dialog[active]"
            <?php echo ($record->active) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="csbformat-active"
            class="cb">
            <?php echo I18n::__('csbformat_label_active') ?>
        </label>
    </div>
        <div class="row <?php echo ($record->hasError('method')) ? 'error' : ''; ?>">
        <label
            for="csbformat-method">
            <?php echo I18n::__('csbformat_label_method') ?>
        </label>
        <input
            id="csbformat-method"
            type="text"
            name="dialog[method]"
            value="<?php echo htmlspecialchars($record->method) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of csbformat edit form -->