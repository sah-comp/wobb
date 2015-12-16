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
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_sumweight') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_sumtotaldprice') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_avgmfa') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('analysis_label_avgweight') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('analysis_label_avgdprice') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($record->with(' ORDER BY id ')->ownAnalysisitem as $_id => $_analysisitem): ?>
            <div>
                <input type="hidden" name="dialog[type]" value="analysis" />
                <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
            </div>
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
                            value="<?php echo htmlspecialchars($_analysisitem->piggery) ?>"
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
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgmfa]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
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
                            name="dialog[ownAnalysisitem][<?php echo $_id ?>][avgdprice]"
                            value="<?php echo htmlspecialchars($_analysisitem->decimal('avgdprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                </div>
            </fieldset>
            <?php endforeach ?>
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
