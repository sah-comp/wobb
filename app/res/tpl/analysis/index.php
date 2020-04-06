<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("analysis_h1") ?></h1>
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
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('analysis_history_legend') ?></legend>
			<h2 class="year"><?php echo $fiscalyear ?></h2>
            <div class="row">
                <div class="span2">
                    <label><?php echo I18n::__('analysis_label_company_id') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('analysis_label_startdate') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('analysis_label_enddate') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('analysis_label_weekofyear') ?></label>
                </div>                
                <div class="span6">
                    &nbsp;
                </div>
            </div>
            
            <?php foreach ($records as $_id => $_record): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('analysis_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/analysis/analysis/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->company->name) ?>
                        </div>
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->localizedDate('startdate')) ?>
                        </div>
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->localizedDate('enddate')) ?>
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->weekOfYear()) ?>
                        </div>
                        <div class="span6">
                            <span class="analysis-info"><?php echo ( $_record->dirty ) ? I18n::__('analysis_isdirty') : "&nbsp;"  ?></div>
                        </div>
                    </div>
                </a>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Purchase buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('analysis_submit') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
