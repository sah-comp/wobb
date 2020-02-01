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
            for="plan-company">
            <?php echo I18n::__('plan_label_company') ?>
        </label>
        <select
            id="plan-company"
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
    <div class="row <?php echo ($record->hasError('period')) ? 'error' : ''; ?>">
        <label
            for="plan-period">
            <?php echo I18n::__('plan_label_period') ?>
        </label>
        <input
            id="plan-period"
            type="number"
            min="1"
            step="1"
            max="52"
            name="dialog[period]"
            value="<?php echo htmlspecialchars($record->period) ?>" />
		<span class="info"><?php echo I18n::__('plan_info_period') ?></span>
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
            <div class="span3">
                <label>
                    <?php echo I18n::__('plan_deliverer_label_person') ?>
                </label>
            </div>
            <div class="span1">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_piggery') ?>
                </label>
            </div>
            <div class="span1">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_baseprice') ?>
                </label>
            </div>
			<div class="span3">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_totalnet') ?>
                </label>
			</div>
            <div class="span1">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_mfa') ?>
                </label>
            </div>
            <div class="span1">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_weight') ?>
                </label>
            </div>
            <div class="span1">
                <label class="number">
                    <?php echo I18n::__('plan_deliverer_label_price') ?>
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
		
        <!-- grid based footer -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span3">
                <label>
                    <?php echo I18n::__('plan_label_total') ?>
                </label>
            </div>
            <div class="span1">
	            <input
	                id="plan-piggery"
	                class="autowidth number"
	                type="text"
	                name="dialog[piggery]"
	                value="<?php echo ($record->piggery) ?>" />
            </div>
            <div class="span1">
                &nbsp;
            </div>
			<div class="span3">
	            <input
	                type="text"
	                class="number"
	                readonly="readonly"
	                name="dialog[totalnet]"
	                value="<?php echo htmlspecialchars($record->decimal('totalnet', 3)) ?>"
                
	            />
			</div>
            <div class="span1">
	            <input
	                type="text"
	                class="number"
	                readonly="readonly"
	                name="dialog[meanmfa]"
	                value="<?php echo htmlspecialchars($record->decimal('meanmfa', 3)) ?>"
                
	            />
            </div>
            <div class="span1">
	            <input
	                type="text"
	                class="number"
	                readonly="readonly"
	                name="dialog[meanweight]"
	                value="<?php echo htmlspecialchars($record->decimal('meanweight', 3)) ?>"
                
	            />
            </div>
            <div class="span1">
	            <input
	                type="text"
	                class="number"
	                readonly="readonly"
	                name="dialog[meandprice]"
	                value="<?php echo htmlspecialchars($record->decimal('meandprice', 3)) ?>"
                
	            />
            </div>
        </div>
        <!-- end of grid based footer -->
	

    </fieldset>
</div>
<!-- end of plan edit form -->