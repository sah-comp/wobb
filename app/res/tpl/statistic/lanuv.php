<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('lanuv_h1') ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-lanuv"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <div>
            <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>
        
        <fieldset>
            <legend class="verbose"><?php echo I18n::__('lanuv_legend') ?></legend>
            <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
                <label
                    for="lanuv-company">
                    <?php echo I18n::__('lanuv_label_company') ?>
                </label>
                <select
                    id="lanuv-company"
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
                    for="lanuv-startdate">
                    <?php echo I18n::__('lanuv_label_startdate') ?>
                </label>
                <input
                    id="lanuv-startdate"
                    type="date"
                    name="dialog[startdate]"
                    value="<?php echo htmlspecialchars($record->startdate) ?>"
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
                <label
                    for="lanuv-enddate">
                    <?php echo I18n::__('lanuv_label_enddate') ?>
                </label>
                <input
                    id="lanuv-enddate"
                    type="date"
                    name="dialog[enddate]"
                    value="<?php echo htmlspecialchars($record->enddate) ?>"
                    required="required" />
            </div>
        </fieldset>
        
        <!-- form details -->
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('statistic_lanuvitem_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('lanuv_label_quality') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lanuv_label_piggery') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lanuv_label_sumweight') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lanuv_label_sumtotallanuvprice') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lanuv_label_avgmfa') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lanuv_label_avgweight') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('lanuv_label_avgpricelanuv') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($record->with(' ORDER BY id ')->ownLanuvitem as $_id => $_lanuvitem): ?>
            <div>
                <input type="hidden" name="dialog[type]" value="lanuv" />
                <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
            </div>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('statistic_lanuvitem_legend') ?></legend>
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownLanuvitem][<?php echo $_id ?>][type]"
                        value="lanuvitem" />
                    <input
                        type="hidden"
                        name="dialog[ownLanuvitem][<?php echo $_id ?>][id]"
                        value="<?php echo $_id ?>" />
                </div>
                <div class="row">
                    <div class="span1">
                        <input
                            type="text"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][quality]"
                            value="<?php echo htmlspecialchars($_lanuvitem->quality) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('piggery', 0)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][sumweight]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('sumweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][sumtotallanuvprice]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('sumtotallanuvprice', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][avgmfa]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('avgmfa', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][avgweight]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('avgweight', 3)) ?>"
                            readonly="readonly"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownLanuvitem][<?php echo $_id ?>][avgpricelanuv]"
                            value="<?php echo htmlspecialchars($_lanuvitem->decimal('avgpricelanuv', 3)) ?>"
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
            <a
                href="<?php echo Url::build(sprintf("/statistic/send/%d", $record->getId())) ?>"
                class="btn">
                <?php echo I18n::__('lanuv_href_lanuv_report') ?>
            </a>
            <a
                href="<?php echo Url::build(sprintf("/statistic/csv/%d", $record->getId())) ?>"
                class="btn">
                <?php echo I18n::__('lanuv_href_export_csv') ?>
            </a>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('statistic_submit_lanuv') ?>" />
        </div>
        <!-- End of statistic buttons -->
    </form>
</article>
