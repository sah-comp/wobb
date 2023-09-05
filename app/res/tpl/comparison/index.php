<?php
Flight::setlocale();
?>
<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("comparison_h1_index") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-comparison"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('comparison_history_legend') ?></legend>
            <h2 class="year"><?php echo $fiscalyear ?></h2>
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('comparison_label_week') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('comparison_label_person') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('comparison_label_startdate') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('comparison_label_enddate') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('comparison_label_piggery') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('comparison_label_baseprice') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('comparison_label_stats') ?></label>
                </div>
                <div class="span1">
                    &nbsp;
                </div>
            </div>
            <?php foreach ($records as $_id => $_record) :
                $ts = strtotime($_record->startdate);
                $ts_end = strtotime($_record->enddate);
                ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('comparison_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/comparison/edit/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span1">
                            <?php echo strftime("%V", $ts) ?>
                        </div>
                        <div class="span2">
                            hm
                        </div>
                        <div class="span2">
                            <?php echo strftime("%a, %e. %b", $ts) ?>
                        </div>
                        <div class="span2">
                            <?php echo strftime("%a, %e. %b", $ts_end) ?>
                        </div>
                        <div class="span1 number">
                            <?php echo $_record->piggery ?>
                        </div>
                        <div class="span2 number">
                            <?php echo $_record->decimal('baseprice', 3) ?>
                        </div>
                        <div class="span1">
                            <span class="plan-info">Info</span>
                        </div>
                        <div class="span1 number">
                            <?php if ($_record->wasCalculated()) : ?>
                                &nbsp;
                            <?php else : ?>
                                <a
                                    href="<?php echo Url::build(sprintf('/comparison/drop/%d', $_record->getId())) ?>"
                                    class="ir delete ask"
                                    data-question="<?php echo I18n::__('comparison_confirm_delete') ?>"
                                    title="<?php echo I18n::__('comparison_title_delete_csb') ?>"><?php echo I18n::__('comparison_link_delete_csb') ?></a>
                            <?php endif ?>
                        </div>
                    </div>
                </a>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- comparison buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('comparison_submit') ?>" />
        </div>
        <!-- End of comparison buttons -->
    </form>
</article>
