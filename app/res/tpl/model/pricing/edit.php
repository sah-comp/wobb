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
<!-- pricing edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('pricing_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="pricing-name">
            <?php echo I18n::__('pricing_label_name') ?>
        </label>
        <input
            id="pricing-name"
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
            id="pricing-active"
            type="checkbox"
            name="dialog[active]"
            <?php echo ($record->active) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="pricing-active"
            class="cb">
            <?php echo I18n::__('pricing_label_active') ?>
        </label>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('pricing_legend') ?></legend>
    <!-- grid based header -->
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <label>
                <?php echo I18n::__('pricing_label_margin_kind') ?>
            </label>
        </div>
        <div class="span2">
            <label>
                <?php echo I18n::__('pricing_label_margin_low') ?>
            </label>
        </div>
        <div class="span2">
            <label>
                <?php echo I18n::__('pricing_label_margin_heigh') ?>
            </label>
        </div>
        <div class="span2">
            <label>
                <?php echo I18n::__('pricing_label_margin_operator') ?>
            </label>
        </div>
        <div class="span1">
            <label>
                <?php echo I18n::__('pricing_label_margin_value') ?>
            </label>
        </div>
    </div>
    <!-- end of grid based header -->
    <!-- grid based data -->
    <?php $_margins = $record->with(' ORDER BY kind DESC, lo')->ownMargin ?>
    <?php $_margins[] = R::dispense('margin') ?>
    <?php $_i = 0 ?>
    <?php foreach ($_margins as $_id => $_margin): ?>
        <?php $_i++  ?>
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span2">
            <input type="hidden" name="dialog[ownMargin][<?php echo $_i ?>][type]" value="<?php echo $_margin->getMeta('type') ?>" />
            <input type="hidden" name="dialog[ownMargin][<?php echo $_i ?>][id]" value="<?php echo $_margin->getId() ?>" />
            <select
                id="pricing-margin-<?php echo $_i ?>-kind"
                name="dialog[ownMargin][<?php echo $_i ?>][kind]">
                <option value=""><?php echo I18n::__('margin_label_select') ?></option>
                <?php foreach (array('weight', 'mfa', 'mfasub') as $_kind): ?>
                <option
                    value="<?php echo $_kind ?>"
                    <?php echo ($_margin->kind == $_kind) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars(I18n::__('margin_label_'.$_kind)) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span2">
            <input
                id="pricing-margin-<?php echo $_i ?>-lo"
                class="autowidth"
                type="text"
                name="dialog[ownMargin][<?php echo $_i ?>][lo]"
                value="<?php echo htmlspecialchars($_margin->decimal('lo')) ?>" />
        </div>
        <div class="span2">
            <input
                id="pricing-margin-<?php echo $_i ?>-hi"
                class="autowidth"
                type="text"
                name="dialog[ownMargin][<?php echo $_i ?>][hi]"
                value="<?php echo htmlspecialchars($_margin->decimal('hi')) ?>" />
        </div>
        <div class="span2">
            <select
                id="pricing-margin-<?php echo $_i ?>-op"
                name="dialog[ownMargin][<?php echo $_i ?>][op]">
                <option value=""><?php echo I18n::__('margin_label_select') ?></option>
                <?php foreach (array('-', '+', '=') as $_op): ?>
                <option
                    value="<?php echo $_op ?>"
                    <?php echo ($_margin->op == $_op) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars(I18n::__('margin_label_'.$_op)) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span1">
            <input
                id="pricing-margin-<?php echo $_i ?>-value"
                class="autowidth"
                type="text"
                name="dialog[ownMargin][<?php echo $_i ?>][value]"
                value="<?php echo htmlspecialchars($_margin->decimal('value')) ?>" />
        </div>
    </div>
    <?php endforeach ?>
    <!-- end of grid based data -->
</fieldset>
<!-- end of pricing edit form -->