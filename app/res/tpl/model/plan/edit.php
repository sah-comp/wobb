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
<!-- plan edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('plan_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="planning-company">
            <?php echo I18n::__('planning_label_company') ?>
        </label>
        <select
            id="planning-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company): ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('pubdate')) ? 'error' : ''; ?>">
        <label
            for="plan-pubdate">
            <?php echo I18n::__('plan_label_pubdate') ?>
        </label>
        <input
            id="plan-pubdate"
            type="date"
            name="dialog[pubdate]"
            value="<?php echo htmlspecialchars($record->pubdate) ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('plan_info_pubdate') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('baseprice')) ? 'error' : ''; ?>">
        <label
            for="plan-baseprice">
            <?php echo I18n::__('plan_label_baseprice') ?>
        </label>
        <input
            id="plan-baseprice"
            type="text"
            class="number"
            name="dialog[baseprice]"
            value="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
            placeholder="<?php echo htmlspecialchars($record->getLatest()->decimal('baseprice', 3)) ?>"
            required="required" />
		<p class="info"><?php echo I18n::__('plan_info_baseprice') ?></p>
    </div>
</fieldset>
<div
    class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'deliverer-tabs',
        'tabs' => array(
            'plan-deliverers' => I18n::__('plan_deliverer_tab')
        ),
        'default_tab' => 'plan-deliverers'
    )) ?>
    <fieldset
        id="plan-deliverers"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('plan_deliverers_legend') ?></legend>
        <!-- grid based header -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('deliverer_label_person') ?>
                </label>
            </div>
            <div class="span2">
                <label>
                    <?php echo I18n::__('deliverer_label_pricing') ?>
                </label>
            </div>
            <div class="span7">
                <label class="number">
                    <?php echo I18n::__('deliverer_label_piggery') ?>
                </label>
            </div>
        </div>
        <!-- end of grid based header -->
        <!-- grid based data -->
        <div
            id="plan-<?php echo $record->getId() ?>-deliverer-container"
            class="container attachable detachable sortable">
		<?php $_deliverers = $record->getDeliverers() ?>
        <?php if (count($_deliverers) == 0) $_deliverers[] = R::dispense('deliverer') ?>
        <?php
		$index = 0;
		?>
        <?php foreach ($_deliverers as $_deliverer_id => $_deliverer):
			$index++;
		?>
            <?php Flight::render('model/plan/own/deliverer', array(
                'record' => $record,
                '_deliverer' => $_deliverer,
                'index' => $index
            )) ?>
        <?php endforeach ?>
        </div>
        <!-- end of grid based data -->
    </fieldset>
</div>
<!-- end of plan edit form -->