<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("lab_h1") ?></h1>
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
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('lab_history_legend') ?></legend>
            
            <div class="row">
                <div class="span3">
                    <label><?php echo I18n::__('lab_label_company_id') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('lab_label_startdate') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('lab_label_enddate') ?></label>
                </div>
                <div class="span3">
                    <label><?php echo I18n::__('lab_label_pricing') ?></label>
                </div>                
                <div class="span2">
                    <label class="number"><?php echo I18n::__('lab_label_baseprice') ?></label>
                </div>
            </div>
            
            <?php foreach ($records as $_id => $_record): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('lab_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/lab/lab/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span3">
                            <?php echo htmlspecialchars($_record->getCompanyName()) ?>
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->localizedDate('startdate')) ?>
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->localizedDate('enddate')) ?>
                        </div>
                        <div class="span3">
                            <?php echo htmlspecialchars($_record->getPricingName()) ?>
                        </div>
                        <div class="span2 number">
                            <?php echo $_record->decimal('baseprice', 3) ?>
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
                value="<?php echo I18n::__('lab_submit') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>