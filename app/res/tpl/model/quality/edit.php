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
<!-- quality edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('quality_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('sequence')) ? 'error' : ''; ?>">
        <label
            for="quality-sequence">
            <?php echo I18n::__('quality_label_sequence') ?>
        </label>
        <input
            id="quality-sequence"
            type="number"
            min="0"
            step="10"
            max="99999999"
            name="dialog[sequence]"
            value="<?php echo ($record->sequence) ?>" />
        <p class="info"><?php echo I18n::__('quality_info_sequence') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="quality-name">
            <?php echo I18n::__('quality_label_name') ?>
        </label>
        <input
            id="quality-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('desc')) ? 'error' : ''; ?>">
        <label
            for="quality-desc">
            <?php echo I18n::__('quality_label_desc') ?>
        </label>
        <input
            id="quality-desc"
            type="text"
            name="dialog[desc]"
            value="<?php echo htmlspecialchars($record->desc) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('enabled')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[enabled]"
            value="0" />
        <input
            id="quality-enabled"
            type="checkbox"
            name="dialog[enabled]"
            <?php echo ($record->enabled) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="quality-enabled"
            class="cb">
            <?php echo I18n::__('quality_label_enabled') ?>
        </label>
    </div>
</fieldset>
<!-- end of quality edit form -->