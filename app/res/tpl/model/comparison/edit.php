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
<!-- comparison edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('comparison_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="comparison-company">
            <?php echo I18n::__('comparison_label_company') ?>
        </label>
        <select
            id="comparison-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company) : ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('startdate')) ? 'error' : ''; ?>">
        <label
            for="comparison-startdate">
            <?php echo I18n::__('comparison_label_startdate') ?>
        </label>
        <input
            id="comparison-startdate"
            type="date"
            placeholder="yyyy-mm-dd"
            name="dialog[startdate]"
            value="<?php echo htmlspecialchars($record->startdate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
        <label
            for="comparison-enddate">
            <?php echo I18n::__('comparison_label_enddate') ?>
        </label>
        <input
            id="comparison-enddate"
            type="date"
            placeholder="yyyy-mm-dd"
            name="dialog[enddate]"
            value="<?php echo htmlspecialchars($record->enddate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('baseprice')) ? 'error' : ''; ?>">
        <label
            for="comparison-baseprice-pubdate">
            <?php echo I18n::__('comparison_label_baseprice') ?>
        </label>
        <input
            id="comparison-baseprice"
            type="text"
            class="number"
            name="dialog[baseprice]"
            value="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
            placeholder="<?php echo htmlspecialchars($record->getLatest()->decimal('baseprice', 3)) ?>"
            required="required" />
    </div>
    <div class="row nomargins">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span1 <?php echo ($record->hasError('person')) ? 'error' : ''; ?>">
            <label
                for="comparison-person">
                <?php echo I18n::__('comparison_label_person') ?>
            </label>
        </div>
        <div class="span1 <?php echo ($record->hasError('piggery')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_piggery') ?>
            </label>
        </div>
        <div class="span2 <?php echo ($record->hasError('totalweight')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_totalweight') ?>
            </label>
        </div>
        <div class="span1 <?php echo ($record->hasError('meanmfa')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_meanmfa') ?>
            </label>
        </div>
        <div class="span1 <?php echo ($record->hasError('meanweight')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_meanweight') ?>
            </label>
        </div>
        <div class="span2 <?php echo ($record->hasError('totalnet')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_totalnet') ?>
            </label>
        </div>
        <div class="span1 <?php echo ($record->hasError('diff')) ? 'error' : ''; ?>">
            <label class="number">
                <?php echo I18n::__('comparison_label_diff') ?>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="span3">
            &nbsp;
        </div>
        <div class="span1">
            <select
                id="comparison-person"
                name="dialog[person_id]"
                style="width: 100%;">
                <option value=""><?php echo I18n::__('deliverer_label_select') ?></option>
                <?php foreach (R::find('person', " enabled = 1 ORDER BY name") as $_person_id => $_person) : ?>
                <option
                    value="<?php echo $_person->getId() ?>"
                    <?php echo ($record->person_id == $_person->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_person->nickname . ' – ' . $_person->name) ?></option>   
                <?php endforeach ?>
            </select>
        </div>
        <div class="span1 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[piggery]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->piggery) ?>" />
        </div>
        <div class="span2 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[totalweight]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('totalweight', 2)) ?>" />
        </div>
        <div class="span1 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[meanmfa]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('meanmfa', 2)) ?>" />
        </div>
        <div class="span1 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[meanweight]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('meanweight', 2)) ?>" />
        </div>
        <div class="span2 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[totalnet]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('totalnet', 2)) ?>" />
        </div>
        <div class="span1 number">
            <input
                type="text"
                class="number autowidth"
                name="dialog[diff]"
                readonly="readonly"
                value="<?php echo htmlspecialchars($record->decimal('diff', 2)) ?>" />
        </div>
    </div>
</fieldset>
<div
    class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'deliverer-tabs',
        'tabs' => array(
            'comparison-deliverers' => I18n::__('comparison_deliverer_tab')
        ),
                             'default_tab' => 'comparison-deliverers'
    )) ?>
    <fieldset
        id="comparison-deliverers"
        class="tab">
        <legend class="verbose"><?php echo I18n::__('comparison_deliverers_legend') ?></legend>
        <!-- grid based header -->
        <div class="row">
            <div class="span1">&nbsp;</div>
            <div class="span2">&nbsp;</div>
            <div class="span1">
                <label>
                    <?php echo I18n::__('comparison_deliverer_label_person') ?>
                </label>
            </div>
            <div class="span5">&nbsp;</div>
            <div class="span2">&nbsp;</div>
            <div class="span1">&nbsp;</div>
        </div>
        <!-- end of grid based header -->
        <!-- grid based data -->
        <div
            id="comparison-<?php echo $record->getId() ?>-deliverer-container"
            class="container attachable detachable sortable">
        <?php $_deliverers = $record->getDeliverers() ?>
        <?php if (count($_deliverers) == 0) {
            $_deliverers[] = R::dispense('deliverer');
        } ?>
        <?php
        $index = 0;
        ?>
        <?php foreach ($_deliverers as $_deliverer_id => $_deliverer) :
            $index++;
            ?>
            <?php Flight::render('model/comparison/own/deliverer', array(
                'record' => $record,
                '_deliverer' => $_deliverer,
                'index' => $index
            )) ?>
        <?php endforeach ?>
        </div>
        <!-- end of grid based data -->
        
        <!-- grid based footer -->
        <!-- end of grid based footer -->
    

    </fieldset>
</div>
<!-- end of comparison edit form -->