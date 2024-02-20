<?php 
Flight::setlocale();
?>
<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("planning_h1_index") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-planning"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('planning_history_legend') ?></legend>
            <h2 class="year"><?php echo $fiscalyear ?></h2>
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('planning_label_week') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('planning_label_date') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('planning_label_piggery') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('planning_label_baseprice') ?></label>
                </div>
                <div class="span5">
                    <label><?php echo I18n::__('planning_label_stats') ?></label>
                </div>
                <div class="span1">
                    &nbsp;
                </div>
            </div>
            <?php foreach ($records as $_id => $_record):
				$ts = strtotime( $_record->pubdate );
			?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('planning_history_item_legend') ?></legend>
                <a
                    href="<?php echo Url::build(sprintf('/planning/edit/%d', $_record->getId())) ?>">
                    <div class="row">
                        <div class="span1">
                            <?php 
                            //echo strftime( "%V", $ts) 
                            echo date( "W", $ts) 
                            ?>
                        </div>
                        <div class="span2">
                            <?php 
                            //echo strftime( "%a, %e. %b", $ts) 
                            echo date( "d.m.Y", $ts) 
                            ?>
                        </div>
                        <div class="span1 number">
                            <?php echo $_record->piggery ?>
                        </div>
                        <div class="span2 number">
                            <?php echo $_record->decimal('baseprice', 3) ?>
                        </div>
                        <div class="span5">
							<span class="plan-info"><?php echo I18n::__('planning_text_footer', null, [$_record->period]) ?></span>
                        </div>
                        <div class="span1 number">
                            <?php if ( $_record->wasCalculated() ): ?>
                                &nbsp;
                            <?php else: ?>
                                <a
                                    href="<?php echo Url::build(sprintf('/planning/drop/%d', $_record->getId())) ?>"
                                    class="ir delete ask"
                                    data-question="<?php echo I18n::__('planning_confirm_delete') ?>"
                                    title="<?php echo I18n::__('planning_title_delete_csb') ?>"><?php echo I18n::__('planning_link_delete_csb') ?></a>
                            <?php endif ?>
                        </div>
                    </div>
                </a>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Planning buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('planning_submit') ?>" />
        </div>
        <!-- End of Planning buttons -->
    </form>
</article>
