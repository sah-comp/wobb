<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('piggery_h1') ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-piggery"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">

        <div>
            <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>

        <fieldset>
            <legend class="verbose"><?php echo I18n::__('piggery_legend') ?></legend>
            <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
                <label
                    for="piggery-company">
                    <?php echo I18n::__('piggery_label_company') ?>
                </label>
                <select
                    id="piggery-company"
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
                    for="piggery-startdate">
                    <?php echo I18n::__('piggery_label_startdate') ?>
                </label>
                <input
                    id="piggery-startdate"
                    type="date"
					placeholder="yyyy-mm-dd"
                    name="dialog[startdate]"
                    value="<?php echo htmlspecialchars($record->startdate) ?>"
                    required="required" />
            </div>
            <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
                <label
                    for="piggery-enddate">
                    <?php echo I18n::__('piggery_label_enddate') ?>
                </label>
                <input
                    id="piggery-enddate"
                    type="date"
					placeholder="yyyy-mm-dd"
                    name="dialog[enddate]"
                    value="<?php echo htmlspecialchars($record->enddate) ?>"
                    required="required" />
            </div>
        </fieldset>

        <!-- form details -->
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('statistic_piggeryitem_legend') ?></legend>

            <div class="row">
                <div class="span3">
                    &nbsp;
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('piggery_label_pubdate') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('piggery_label_piggery') ?></label>
                </div>
            </div>

            <?php foreach ($record->with('ORDER BY pubdate')->ownPiggeryitem as $_id => $pi): ?>
            <div class="row">
                <div class="span3">
                    &nbsp;
                </div>
                <div class="span2">
                    <?php echo htmlspecialchars($pi->localizedDate('pubdate')) ?>
                </div>
                <div class="span2 number">
                    <?php echo htmlspecialchars($pi->decimal('stockcount', 0)) ?>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="row">
                <div class="span3">
                    &nbsp;
                </div>
                <div class="span2 number">
                    <?php echo I18n::__('piggery_label_total_stockcount') ?>
                </div>
                <div class="span2 number">
                    <?php echo htmlspecialchars($record->decimal('stockcount', 0)) ?>
                </div>
            </div>


        </fieldset>
        <!-- end of form details -->

        <!-- piggery buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('piggery_submit') ?>" />
        </div>
        <!-- End of piggery buttons -->
    </form>
</article>
