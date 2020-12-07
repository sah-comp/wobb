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
<!-- person edit subform nonqs -->
<fieldset
    id="person-<?php echo $record->getId() ?>-ownnonqs-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_nonqs') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/nonqs/%d', $_nonqs->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-ownnonqs-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/nonqs/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-nonqs-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
<div>
    <input
        type="hidden"
        name="dialog[ownNonqs][<?php echo $index ?>][type]"
        value="<?php echo $_nonqs->getMeta('type') ?>" />
    <input
        type="hidden"
        name="dialog[ownNonqs][<?php echo $index ?>][id]"
        value="<?php echo $_nonqs->getId() ?>" />
</div>
<div class="row <?php echo ($_nonqs->hasError('earmark')) ? 'error' : ''; ?>">
    <label
        for="person-<?php echo $record->getId() ?>-nonqs-<?php echo $index ?>-earmark">
        <?php echo I18n::__('nonqs_label_earmark') ?>
    </label>
    <input
        id="person-<?php echo $record->getId() ?>-nonqs-<?php echo $index ?>-earmark"
        type="text"
        name="dialog[ownNonqs][<?php echo $index ?>][earmark]"
        value="<?php echo htmlspecialchars($_nonqs->earmark) ?>" />
</div>
</fieldset>
<!-- /nonqs edit subform -->
