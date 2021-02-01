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
<!-- person edit subform condition -->
<fieldset
    id="person-<?php echo $record->getId() ?>-owncondition-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_condition') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/condition/%d', $_condition->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-owncondition-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/condition/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-condition-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
<div>
    <input
        type="hidden"
        name="dialog[ownCondition][<?php echo $index ?>][type]"
        value="<?php echo $_condition->getMeta('type') ?>" />
    <input
        type="hidden"
        name="dialog[ownCondition][<?php echo $index ?>][id]"
        value="<?php echo $_condition->getId() ?>" />
</div>
<div class="row <?php echo ($_condition->hasError('label')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-label">
        <?php echo I18n::__('condition_label_label') ?>
    </label>
    <select
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-label"
        name="dialog[ownCondition][<?php echo $index ?>][label]">
        <option value=""><?php echo I18n::__('condition_label_select') ?></option>
        <?php foreach ($_condition->getLabels() as $_label): ?>
        <option
            value="<?php echo $_label ?>"
            <?php echo ($_condition->label == $_label) ? 'selected="selected"' : '' ?>>
            <?php echo I18n::__('condition_label_'.$_label) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
<div class="row <?php echo ($_condition->hasError('content')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-content">
        <?php echo I18n::__('condition_label_content') ?>
    </label>
    <textarea
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-content"
        name="dialog[ownCondition][<?php echo $index ?>][content]"
        rows="2"
        cols="60"><?php echo htmlspecialchars($_condition->content) ?></textarea>
</div>
<div class="row <?php echo ($_condition->hasError('value')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-value">
        <?php echo I18n::__('condition_label_value') ?>
    </label>
    <input
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-value"
        type="text"
        name="dialog[ownCondition][<?php echo $index ?>][value]"
        value="<?php echo htmlspecialchars($_condition->decimal('value', 3)) ?>" />
    <p class="info"><?php echo I18n::__('condition_info_value') ?></p>
</div>
<div class="row <?php echo ($_condition->hasError('precondition')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-precondition">
        <?php echo I18n::__('condition_label_precondition') ?>
    </label>
    <select
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-precondition"
        name="dialog[ownCondition][<?php echo $index ?>][precondition]">
        <option value=""><?php echo I18n::__('condition_precondition_select') ?></option>
        <?php foreach ($_condition->getPreconditions() as $_precondition): ?>
        <option
            value="<?php echo $_precondition ?>"
            <?php echo ($_condition->precondition == $_precondition) ? 'selected="selected"' : '' ?>>
            <?php echo I18n::__('condition_precondition_'.$_precondition) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
<div class="row <?php echo ($_condition->hasError('comparison')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-comparison">
        <?php echo I18n::__('condition_label_comparison') ?>
    </label>
    <select
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-comparison"
        name="dialog[ownCondition][<?php echo $index ?>][comparison]">
        <option value=""><?php echo I18n::__('condition_comparison_select') ?></option>
        <?php foreach ($_condition->getComparisons() as $_comparison): ?>
        <option
            value="<?php echo $_comparison ?>"
            <?php echo ($_condition->comparison == $_comparison) ? 'selected="selected"' : '' ?>>
            <?php echo I18n::__('condition_comparison_'.$_comparison) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
<div class="row <?php echo ($_condition->hasError('cvalue')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-cvalue">
        <?php echo I18n::__('condition_label_cvalue') ?>
    </label>
    <input
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-cvalue"
        type="text"
        name="dialog[ownCondition][<?php echo $index ?>][cvalue]"
        value="<?php echo htmlspecialchars($_condition->decimal('cvalue', 3)) ?>" />
</div>
<div class="row <?php echo ($_condition->hasError('doesnotaffectinvoice')) ? 'error' : ''; ?>">
    <input
        type="hidden"
        name="dialog[ownCondition][<?php echo $index ?>][doesnotaffectinvoice]"
        value="0" />
    <input
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-doesnotaffectinvoice"
        type="checkbox"
        name="dialog[ownCondition][<?php echo $index ?>][doesnotaffectinvoice]"
        <?php echo ($_condition->doesnotaffectinvoice) ? 'checked="checked"' : '' ?>
        value="1" />
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $index ?>-doesnotaffectinvoice"
        class="cb">
        <?php echo I18n::__('var_label_doesnotaffectinvoice') ?>
    </label>
</div>
</fieldset>
<!-- /condition edit subform -->
