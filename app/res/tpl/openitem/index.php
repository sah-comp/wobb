<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("openitem_h1_index") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-openitem-selector"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <fieldset>
            <legend><?php echo I18n::__('openitem_legend_filter') ?></legend>
            <div class="row">
                <label
                    for="openitem-fy">
                    <?php echo I18n::__('openitem_label_sel_fy') ?>
                </label>
                <input
                    id="openitem-fy"
                    type="text"
                    name="dialog[fy]"
                    value="<?php echo htmlspecialchars($_SESSION['openitem']['fy']) ?>"
                    required="required" />
            </div>
            <div class="row">
                <label
                    for="openitem-nickname">
                    <?php echo I18n::__('openitem_label_sel_nickname') ?>
                </label>
                <input
                    id="openitem-nickname"
                    type="text"
                    name="dialog[nickname]"
                    value="<?php echo htmlspecialchars($_SESSION['openitem']['nickname']) ?>"
                    required="required" />
            </div>
            <div class="buttons">
                <a
                    href="<?php echo Url::build("/openitem/clearfilter") ?>"
                    class="btn">
                    <?php echo I18n::__('openitem_clearfilter') ?>
                </a>
                <input
                    type="submit"
                    name="submit"
                    accesskey="s"
                    value="<?php echo I18n::__('openitem_sel_submit') ?>" />
            </div>
        </fielset>
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('openitem_history_legend') ?></legend>
            

            <?php 
                Flight::setlocale();
                $_lastYear = null;
            ?>
            <?php foreach ($records as $_id => $_record): ?>
                <?php if ( $_lastYear != $_record->fy): 
                        $_lastYear = $_record->fy;
                ?>
            <h2 class="year-fiscal"><?php echo $_record->fy ?></h2>
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('openitem_label_name') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('openitem_label_dateofslaughter') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('openitem_label_person_account') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('openitem_label_person_nickname') ?></label>
                </div>
                <div class="span5">
                    <label><?php echo I18n::__('openitem_label_person_id') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('openitem_label_totalgros') ?></label>
                </div>
            </div>
                <?php endif ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('openitem_history_item_legend') ?></legend>
                    <div
                        id="openitem-<?php echo $_record->getId() ?>"
                        class="row openitem-kind-<?php echo $_record->kind ?> openitem-cancel-<?php echo $_record->canceled ?>">
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->name) ?>
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->localizedDate('dateofslaughter')) ?>
                        </div>
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->person->account) ?>
                        </div>
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->person->nickname) ?>
                        </div>
                        <div class="span5">
                            <?php echo htmlspecialchars($_record->person->name) ?>
                        </div>
                        <div class="span2 number">
                            <?php echo htmlspecialchars($_record->decimal('totalgros', 2)) ?>
                        </div>
                    </div>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
    </form>
</article>
