<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('analysis_h1') ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-analysis"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <div>
            <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>
        
        <fieldset>
            <legend class="verbose"><?php echo I18n::__('analysis_legend') ?></legend>
            <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
                <label
                    for="analysis-company">
                    <?php echo I18n::__('analysis_label_company') ?>
                </label>
                <select
                    id="analysis-company"
                    name="dialog[company_id]">
                    <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company): ?>
                    <option
                        value="<?php echo $_company->getId() ?>"
                        <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
                    <?php endforeach ?>
                </select>
            </div>
            <div class="row <?php echo ($record->hasError('startdate')) ? 'error' : ''; ?>">
                <label
                    for="analysis-startdate">
                    <?php echo I18n::__('analysis_label_startdate') ?>
                </label>
                <input
                    id="analysis-startdate"
                    type="date"
                    name="dialog[startdate]"
                    value="<?php echo htmlspecialchars($record->startdate) ?>"
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
                <label
                    for="analysis-enddate">
                    <?php echo I18n::__('analysis_label_enddate') ?>
                </label>
                <input
                    id="analysis-enddate"
                    type="date"
                    name="dialog[enddate]"
                    value="<?php echo htmlspecialchars($record->enddate) ?>"
                    required="required" />
            </div>
        </fieldset>
        
        <!-- form details -->
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('statistic_analysisitem_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('analysis_label_quality') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_piggery') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_piggerypercentage') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_sumweight') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_sumtotaldprice') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_avgmfa') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_avgweight') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_avgdprice') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_avgdpricenet') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($record->withCondition(' kind = 0 ORDER BY id ')->ownAnalysisitem as $_id => $_analysisitem): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('statistic_analysisitem_legend') ?></legend>
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][type]"
                        value="analysisitem" />
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][id]"
                        value="<?php echo $_id ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][kind]"
                        value="<?php echo $_analysisitem->kind ?>" />
                </div>
                <div class="row">
                    <div class="span1">
                        <input
                            type="text"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][quality]"
                            value="<?php echo htmlspecialchars($_analysisitem->quality) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('piggery', 0)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][piggerypercentage]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('piggerypercentage', 2)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][sumweight]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('sumweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][sumtotaldprice]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('sumtotaldprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgmfa]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgweight]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgprice]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgpricenet]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgpricenet', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                </div>
            </fieldset>
            <?php endforeach ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('statistic_analysistotal_legend') ?></legend>
                <div class="row">
                    <div class="span1">
                        &nbsp;
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[piggery]"
                            value="<?php echo htmlspecialchars($record->decimal('piggery', 0)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[piggerypercentage]"
                            value="<?php echo htmlspecialchars($record->decimal('piggerypercentage', 2)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[sumweight]"
                            value="<?php echo htmlspecialchars($record->decimal('sumweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[sumtotaldprice]"
                            value="<?php echo htmlspecialchars($record->decimal('sumtotaldprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[avgmfa]"
                            value="<?php echo htmlspecialchars($record->decimal('avgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[avgweight]"
                            value="<?php echo htmlspecialchars($record->decimal('avgweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[avgprice]"
                            value="<?php echo htmlspecialchars($record->decimal('avgprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[avgpricenet]"
                            value="<?php echo htmlspecialchars($record->decimal('avgpricenet', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                </div>
            </fieldset>
            <?php foreach ($record->withCondition(' kind = 1 ORDER BY id ')->ownAnalysisitem as $_id => $_analysisitem): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('statistic_analysisitem_legend') ?></legend>
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][type]"
                        value="analysisitem" />
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][id]"
                        value="<?php echo $_id ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownAnalysisitem][<?php echo $_id ?>][kind]"
                        value="<?php echo $_analysisitem->kind ?>" />
                </div>
                <div class="row">
                    <div class="span1">
                        <input
                            type="text"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damage]"
                            value="<?php echo htmlspecialchars($_analysisitem->damage) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damagepiggery]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damagepiggery', 0)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damagepiggerypercentage]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damagepiggerypercentage', 2)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damagesumweight]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damagesumweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damagesumtotaldprice]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damagesumtotaldprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damageavgmfa]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damageavgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damageavgweight]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damageavgweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damageavgprice]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damageavgprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][damageavgpricenet]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('damageavgpricenet', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                </div>
            </fieldset>
            <?php endforeach ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('statistic_analysistotal_legend') ?></legend>
                <div class="row">
                    <div class="span1">
                        &nbsp;
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damagepiggery]"
                            value="<?php echo htmlspecialchars($record->decimal('damagepiggery', 0)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damagepiggerypercentage]"
                            value="<?php echo htmlspecialchars($record->decimal('damagepiggerypercentage', 2)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damagesumweight]"
                            value="<?php echo htmlspecialchars($record->decimal('damagesumweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damagesumtotaldprice]"
                            value="<?php echo htmlspecialchars($record->decimal('damagesumtotaldprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damageavgmfa]"
                            value="<?php echo htmlspecialchars($record->decimal('damageavgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damageavgweight]"
                            value="<?php echo htmlspecialchars($record->decimal('damageavgweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damageavgprice]"
                            value="<?php echo htmlspecialchars($record->decimal('damageavgprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[damageavgpricenet]"
                            value="<?php echo htmlspecialchars($record->decimal('damageavgpricenet', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                </div>
            </fieldset>
        </fieldset>
        <!-- end of form details -->
        
        <!-- statistic buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('statistic_submit_analysis') ?>" />
        </div>
        <!-- End of statistic buttons -->
    </form>
</article>
