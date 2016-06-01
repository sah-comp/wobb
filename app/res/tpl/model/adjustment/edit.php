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
<!-- adjustment edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('adjustment_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('pubdate')) ? 'error' : ''; ?>">
        <label
            for="adjustment-pubdate">
            <?php echo I18n::__('adjustment_label_pubdate') ?>
        </label>
        <input
            id="adjustment-pubdate"
            type="date"
            name="dialog[pubdate]"
            value="<?php echo htmlspecialchars($record->pubdate) ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('adjustment_info_pubdate') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="adjustment-company">
            <?php echo I18n::__('adjustment_label_company') ?>
        </label>
        <select
            id="adjustment-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company): ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
</fieldset>
<div
    class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'adjustment-tabs',
        'tabs' => array(
            'adjustment-adjustmentitems' => I18n::__('adjustment_adjustmentitem_tab')
        ),
        'default_tab' => 'adjustment-adjustmentitems'
    )) ?>
    <fieldset
        id="adjustment-adjustmentitems"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('adjustmentitem_legend') ?></legend>
        <!-- grid based header -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('adjustmentitem_label_person') ?>
                </label>
            </div>
            <div class="span1">
                <label>
                    <?php echo I18n::__('adjustmentitem_label_vat') ?>
                </label>
            </div>
            <div class="span2">
                <label class="number">
                    <?php echo I18n::__('adjustmentitem_label_net') ?>
                </label>
            </div>
            <div class="span2">
                <label class="number">
                    <?php echo I18n::__('adjustmentitem_label_vatvalue') ?>
                </label>
            </div>
            <div class="span2">
                <label class="number">
                    <?php echo I18n::__('adjustmentitem_label_gros') ?>
                </label>
            </div>
            <div class="span2">
                <label class="number">
                    <?php echo I18n::__('adjustmentitem_label_invoice') ?>
                </label>
            </div>
        </div>
        <!-- end of grid based header -->
        <!-- grid based data -->
        <div
            id="adjustment-<?php echo $record->getId() ?>-adjustmentitem-container"
            class="container attachable detachable sortable">
        <?php $_adjustmentitems = $record->with(' ORDER BY id')->ownAdjustmentitem ?>
        <?php if (count($_adjustmentitems) == 0) $_adjustmentitems[] = R::dispense('adjustmentitem') ?>
        <?php $index = 0 ?>
        <?php foreach ($_adjustmentitems as $_adjustmentitem_id => $_adjustmentitem): ?>
            <?php $index++  ?>
            <?php Flight::render('model/adjustment/own/adjustmentitem', array(
                'record' => $record,
                '_adjustmentitem' => $_adjustmentitem,
                'index' => $index
            )) ?>
        <?php endforeach ?>
        </div>
        <!-- end of grid based data -->
    </fieldset>
</div>
<!-- end of adjustment edit form -->