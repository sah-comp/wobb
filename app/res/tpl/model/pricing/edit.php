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
<fieldset class="tab">
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
    <div
        id="pricing-<?php echo $record->getId() ?>-margin-container"
        class="container attachable detachable sortable">
    <?php $_margins = $record->with(' ORDER BY kind DESC, lo')->ownMargin ?>
    <?php if (count($_margins) == 0) $_margins[] = R::dispense('margin') ?>
    <?php $index = 0 ?>
    <?php foreach ($_margins as $_margin_id => $_margin): ?>
        <?php $index++  ?>
        <?php Flight::render('model/pricing/own/margin', array(
            'record' => $record,
            '_margin' => $_margin,
            'index' => $index
        )) ?>
    <?php endforeach ?>
    </div>
    <!-- end of grid based data -->
</fieldset>
<!-- end of pricing edit form -->