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
<!-- person edit subform stockman -->
<fieldset
    id="person-<?php echo $record->getId() ?>-ownstockman-<?php echo $index ?>">
    <legend class="verbose"><?php echo I18n::__('person_legend_stockman') ?></legend>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/detach/stockman/%d', $_stockman->getId())) ?>"
    	class="ir detach"
    	title="<?php echo I18n::__('scaffold_detach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-ownstockman-<?php echo $index ?>">
    		<?php echo I18n::__('scaffold_detach') ?>
    </a>
    <a
    	href="<?php echo Url::build(sprintf('/admin/person/attach/own/stockman/%d', $record->getId())) ?>"
    	class="ir attach"
    	title="<?php echo I18n::__('scaffold_attach') ?>"
    	data-target="person-<?php echo $record->getId() ?>-stockman-container">
    		<?php echo I18n::__('scaffold_attach') ?>
    </a>
<div>
    <input
        type="hidden"
        name="dialog[ownStockman][<?php echo $index ?>][type]"
        value="<?php echo $_stockman->getMeta('type') ?>" />
    <input
        type="hidden"
        name="dialog[ownStockman][<?php echo $index ?>][id]"
        value="<?php echo $_stockman->getId() ?>" />
</div>
<div class="row">
	<div class="span3">&nbsp;</div>
	<div class="span3">
	    <input
	        id="person-<?php echo $record->getId() ?>-stockman-<?php echo $index ?>-earmark"
	        type="text"
	        name="dialog[ownStockman][<?php echo $index ?>][earmark]"
	        value="<?php echo htmlspecialchars($_stockman->earmark) ?>" />
	</div>
	<div class="span3">
		col b
	</div>
</div>
</fieldset>
<!-- /stockman edit subform -->
