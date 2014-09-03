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
<!-- stock edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('stock_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('buyer')) ? 'error' : ''; ?>">
        <label
            for="stock-buyer">
            <?php echo I18n::__('stock_label_buyer') ?>
        </label>
        <input
            id="stock-buyer"
            type="text"
            name="dialog[buyer]"
            value="<?php echo htmlspecialchars($record->buyer) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('pubdate')) ? 'error' : ''; ?>">
        <label
            for="stock-pubdate">
            <?php echo I18n::__('stock_label_pubdate') ?>
        </label>
        <input
            id="stock-pubdate"
            type="date"
            name="dialog[pubdate]"
            value="<?php echo htmlspecialchars($record->pubdate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="stock-name">
            <?php echo I18n::__('stock_label_name') ?>
        </label>
        <input
            id="stock-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('earmark')) ? 'error' : ''; ?>">
        <label
            for="stock-earmark">
            <?php echo I18n::__('stock_label_earmark') ?>
        </label>
        <input
            id="stock-earmark"
            type="text"
            name="dialog[earmark]"
            value="<?php echo htmlspecialchars($record->earmark) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('quality')) ? 'error' : ''; ?>">
        <label
            for="stock-quality">
            <?php echo I18n::__('stock_label_quality') ?>
        </label>
        <input
            id="stock-quality"
            type="text"
            name="dialog[quality]"
            value="<?php echo htmlspecialchars($record->quality) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('weight')) ? 'error' : ''; ?>">
        <label
            for="stock-weight">
            <?php echo I18n::__('stock_label_weight') ?>
        </label>
        <input
            id="stock-weight"
            type="text"
            name="dialog[weight]"
            value="<?php echo htmlspecialchars($record->decimal('weight')) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('mfa')) ? 'error' : ''; ?>">
        <label
            for="stock-mfa">
            <?php echo I18n::__('stock_label_mfa') ?>
        </label>
        <input
            id="stock-mfa"
            type="text"
            name="dialog[mfa]"
            value="<?php echo htmlspecialchars($record->decimal('mfa')) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('flesh')) ? 'error' : ''; ?>">
        <label
            for="stock-flesh">
            <?php echo I18n::__('stock_label_flesh') ?>
        </label>
        <input
            id="stock-flesh"
            type="text"
            name="dialog[flesh]"
            value="<?php echo htmlspecialchars($record->decimal('flesh')) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('speck')) ? 'error' : ''; ?>">
        <label
            for="stock-speck">
            <?php echo I18n::__('stock_label_speck') ?>
        </label>
        <input
            id="stock-speck"
            type="text"
            name="dialog[speck]"
            value="<?php echo htmlspecialchars($record->decimal('speck')) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('tare')) ? 'error' : ''; ?>">
        <label
            for="stock-tare">
            <?php echo I18n::__('stock_label_tare') ?>
        </label>
        <input
            id="stock-tare"
            type="text"
            name="dialog[tare]"
            value="<?php echo htmlspecialchars($record->decimal('tare')) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('damage1')) ? 'error' : ''; ?>">
        <label
            for="stock-damage1">
            <?php echo I18n::__('stock_label_damage1') ?>
        </label>
        <input
            id="stock-damage1"
            type="text"
            name="dialog[damage1]"
            value="<?php echo htmlspecialchars($record->damage1) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('damage2')) ? 'error' : ''; ?>">
        <label
            for="stock-damage2">
            <?php echo I18n::__('stock_label_damage2') ?>
        </label>
        <input
            id="stock-damage2"
            type="text"
            name="dialog[damage2]"
            value="<?php echo htmlspecialchars($record->damage2) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('qs')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[qs]"
            value="0" />
        <input
            id="stock-qs"
            type="checkbox"
            name="dialog[qs]"
            <?php echo ($record->qs) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="stock-qs"
            class="cb">
            <?php echo I18n::__('stock_label_qs') ?>
        </label>
    </div>
</fieldset>
<!-- end of stock edit form -->