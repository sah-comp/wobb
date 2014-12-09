<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("purchase_h1_index") ?></h1>
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
            

            <?php 
                Flight::setlocale();
                $_lastYear = null;
            ?>
            <?php foreach ($records as $_id => $_record): ?>
                <?php 
                    $ts = strtotime( $_record->pubdate );
                    $_year = date('Y', $ts);
                ?>
                <?php if ( $_lastYear != $_year): 
                        $_lastYear = $_year;
                ?>
            <h2 class="year-purchase"><?php echo $_year ?></h2>
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('purchase_label_week') ?></label>
                </div>
                <div class="span3">
                    <label><?php echo I18n::__('purchase_label_date') ?></label>
                </div>
                <div class="span1 number">
                    <label><?php echo I18n::__('purchase_label_piggery') ?></label>
                </div>
                <div class="span2 number">
                    <label><?php echo I18n::__('purchase_label_baseprice') ?></label>
                </div>
                <div class="span3">
                    <label><?php echo I18n::__('purchase_label_calcdate') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('purchase_label_attention') ?></label>
                </div>
            </div>
                <?php endif ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('purchase_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/purchase/calculation/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span1">
                            <?php echo strftime( "%V", $ts) ?>
                        </div>
                        <div class="span3">
                            <?php echo strftime( "%a, %e. %b", $ts) ?>
                        </div>
                        <div class="span1">
                            <span class="number"><?php echo $_record->piggery ?></span>
                        </div>
                        <div class="span2">
                            <span class="number"><?php echo $_record->decimal('baseprice', 3) ?></span>
                        </div>
                        <div class="span3">
                            <?php echo ($_record->wasCalculated()) ? htmlspecialchars($_record->localizedDate('calcdate')) : I18n::__('purchase_not_yet_calculated') ?>
                        </div>
                        <div class="span2">
                            <?php echo ($_record->hasStockThatNeedsAttention()) ? I18n::__('purchase_needs_your_attention', null, array(htmlspecialchars($_record->hasStockThatNeedsAttention()))) : I18n::__('purchase_needs_no_attention') ?>
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
