<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("adjustment_h1_index") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-adjustment"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('adjustment_history_legend') ?></legend>
            

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
                    <label><?php echo I18n::__('adjustment_label_week') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('adjustment_label_date') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('adjustment_label_count') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('adjustment_label_net') ?></label>
                </div>
                <div class="span5">
                    <label><?php echo I18n::__('adjustment_label_calcdate') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('adjustment_label_pdf') ?></label>
                </div>
            </div>
                <?php endif ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('adjustment_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/adjustment/edit/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span1">
                            <?php echo strftime( "%V", $ts) ?>
                        </div>
                        <div class="span2">
                            <?php echo strftime( "%a, %e. %b", $ts) ?>
                        </div>
                        <div class="span1 number">
                            <?php echo count($_record->ownAdjustmentitem) ?>
                        </div>
                        <div class="span2 number">
                            <?php echo ( $_record->net ) ? $_record->decimal('net', 2) : I18n::__('adjustment_not_yet_calculated') ?>
                        </div>
                        <div class="span5">
                            <?php echo ($_record->wasCalculated()) ? htmlspecialchars($_record->localizedDate('calcdate')) : I18n::__('adjustment_not_yet_calculated') ?>
                        </div>
                        <div class="span1">
                            <?php if ( $_record->wasBilled() ): ?>
                            <ul class="action">
                                <li>
                                    <sapn
                                        class="ir voucher-internal anchor-substitute"
                                        title="<?php echo I18n::__('adjustment_link_internal_title') ?>"
                                        data-href="<?php echo Url::build('/adjustment/pdf/' . $_record->getId()) ?>"><?php echo I18n::__('adjustment_link_internal') ?></span>
                                </li>
                            </ul>
                            <?php else: ?>
                                &nbsp;
                            <?php endif ?>
                        </div>
                    </div>
                </a>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Adjustment buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('adjustment_submit') ?>" />
        </div>
        <!-- End of Adjustment buttons -->
    </form>
</article>
