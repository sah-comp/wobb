<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('lab_h1') ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-lab"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <div>
            <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>
        
        <fieldset>
            <legend class="verbose"><?php echo I18n::__('lab_legend') ?></legend>
            <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
                <label
                    for="lab-company">
                    <?php echo I18n::__('lab_label_company') ?>
                </label>
                <select
                    id="lab-company"
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
                    for="lab-startdate">
                    <?php echo I18n::__('lab_label_startdate') ?>
                </label>
                <input
                    id="lab-startdate"
                    type="date"
                    name="dialog[startdate]"
                    value="<?php echo htmlspecialchars($record->startdate) ?>"
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
                <label
                    for="lab-enddate">
                    <?php echo I18n::__('lab_label_enddate') ?>
                </label>
                <input
                    id="lab-enddate"
                    type="date"
                    name="dialog[enddate]"
                    value="<?php echo htmlspecialchars($record->enddate) ?>"
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('baseprice')) ? 'error' : ''; ?>">
                <label
                    for="lab-baseprice">
                    <?php echo I18n::__('lab_label_baseprice') ?>
                </label>
                <input
                    id="lab-baseprice"
                    type="text"
                    class="number"
                    name="dialog[baseprice]"
                    value="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
                    placeholder=""
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('pricing_id')) ? 'error' : ''; ?>">
                <label
                    for="lab-pricing">
                    <?php echo I18n::__('lab_label_pricing') ?>
                </label>
                <select
                    id="lab-pricing"
                    name="dialog[pricing_id]">
                    <option value=""><?php echo I18n::__('lab_pricing_please_select') ?></option>
                    <?php foreach (R::find('pricing', ' active = 1 ORDER BY name') as $_id => $_pricing): ?>
                    <option
                        value="<?php echo $_pricing->getId() ?>"
                        <?php echo ($record->pricing_id == $_pricing->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_pricing->name) ?></option>   
                    <?php endforeach ?>
                </select>
            </div>
        </fieldset>
        

        
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
