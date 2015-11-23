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
<!-- var edit subform cost -->
<fieldset
    id="var-<?php echo $record->getId() ?>-owncost-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('var_legend_cost') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/var/detach/cost/%d', $index)) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="var-<?php echo $record->getId() ?>-owncost-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/var/attach/own/cost/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="var-<?php echo $record->getId() ?>-cost-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
<div>
    <input
        type="hidden"
        name="dialog[ownCost][<?php echo $index ?>][type]"
        value="<?php echo $_cost->getMeta('type') ?>" />
    <input
        type="hidden"
        name="dialog[ownCost][<?php echo $index ?>][id]"
        value="<?php echo $_cost->getId() ?>" />
</div>
<div class="row <?php echo ($_cost->hasError('label')) ? 'error' : ''; ?>">
    <label
        for="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-label">
        <?php echo I18n::__('cost_label_label') ?>
    </label>
    <select
        id="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-label"
        name="dialog[ownCost][<?php echo $index ?>][label]">
        <option value=""><?php echo I18n::__('cost_label_select') ?></option>
        <?php foreach ($_cost->getLabels() as $_label): ?>
        <option
            value="<?php echo $_label ?>"
            <?php echo ($_cost->label == $_label) ? 'selected="selected"' : '' ?>>
            <?php echo I18n::__('cost_label_'.$_label) ?>
        </option>
        <?php endforeach ?>
    </select>
</div>
<div class="row <?php echo ($_cost->hasError('content')) ? 'error' : ''; ?>">
    <label
        for="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-content">
        <?php echo I18n::__('cost_label_content') ?>
    </label>
    <textarea
        id="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-content"
        name="dialog[ownCost][<?php echo $index ?>][content]"
        rows="2"
        cols="60"><?php echo htmlspecialchars($_cost->content) ?></textarea>
</div>
<div class="row <?php echo ($_cost->hasError('value')) ? 'error' : ''; ?>">
    <label
        for="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-value">
        <?php echo I18n::__('cost_label_value') ?>
    </label>
    <input
        id="var-<?php echo $record->getId() ?>-cost-<?php echo $index ?>-value"
        type="text"
        name="dialog[ownCost][<?php echo $index ?>][value]"
        value="<?php echo htmlspecialchars($_cost->decimal('value', 3)) ?>" />
</div>
</fieldset>
<!-- /cost edit subform -->
