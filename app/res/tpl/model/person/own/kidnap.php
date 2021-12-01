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
<!-- person edit subform kidnap -->
<fieldset
    id="person-<?php echo $record->getId() ?>-ownkidnap-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_kidnap') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/kidnap/%d', $_kidnap->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-ownkidnap-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/kidnap/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-kidnap-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
<div>
    <input
        type="hidden"
        name="dialog[ownKidnap][<?php echo $index ?>][type]"
        value="<?php echo $_kidnap->getMeta('type') ?>" />
    <input
        type="hidden"
        name="dialog[ownKidnap][<?php echo $index ?>][id]"
        value="<?php echo $_kidnap->getId() ?>" />
</div>

<div class="row">
	<div class="span3">&nbsp;</div>
    <div class="span3">
        <input
            id="person-<?php echo $record->getId() ?>-kidnap-<?php echo $index ?>-vvvo"
            type="text"
            name="dialog[ownKidnap][<?php echo $index ?>][vvvo]"
            value="<?php echo htmlspecialchars($_kidnap->vvvo) ?>" />
    </div>
    <div class="span3">
        <input
            id="person-<?php echo $record->getId() ?>-kidnap-<?php echo $index ?>-earmark"
            type="text"
            name="dialog[ownKidnap][<?php echo $index ?>][earmark]"
            value="<?php echo htmlspecialchars($_kidnap->earmark) ?>" />
    </div>
</div>
</fieldset>
<!-- /kidnap edit subform -->
