<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("purchase_h1") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-purchase"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('purchase_history_legend') ?></legend>
            
            <div class="row">
                <div class="span1">
                    &nbsp;
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('purchase_label_date') ?></label>
                </div>
                <div class="span2 number">
                    <label><?php echo I18n::__('purchase_label_piggery') ?></label>
                </div>
            </div>
            
            <?php foreach ($records as $_id => $_record): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('purchase_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/purchase/day/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span1">
                            &nbsp;
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->localizedDate('pubdate')) ?>
                        </div>
                        <div class="span2 number">
                            <?php echo $_record->piggery ?>
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
                value="<?php echo I18n::__('purchase_submit') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
