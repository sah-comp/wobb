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
<!-- address edit subform condition -->
<fieldset
    id="person-<?php echo $record->getId() ?>-owncondition-<?php echo $_condition->getId() ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_condition') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/condition/%d', $_condition->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-owncondition-<?php echo $_condition->getId() ?>">
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
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-label">
        <?php echo I18n::__('condition_label_label') ?>
    </label>
    <select
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-label"
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
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-content">
        <?php echo I18n::__('condition_label_content') ?>
    </label>
    <textarea
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-content"
        name="dialog[ownCondition][<?php echo $index ?>][content]"
        rows="2"
        cols="60"><?php echo htmlspecialchars($_condition->content) ?></textarea>
</div>
<div class="row <?php echo ($_condition->hasError('value')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-value">
        <?php echo I18n::__('condition_label_value') ?>
    </label>
    <input
        id="person-<?php echo $record->getId() ?>-condition-<?php echo $_condition->getId() ?>-value"
        type="text"
        name="dialog[ownCondition][<?php echo $index ?>][value]"
        value="<?php echo htmlspecialchars($_condition->decimal('value', 3)) ?>"
        required="required" />
</div>
</fieldset>
<!-- /address edit subform -->
