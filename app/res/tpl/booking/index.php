<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("booking_h1_index") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-booking-selector"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <fieldset>
            <legend><?php echo I18n::__('invoice_legend_filter') ?></legend>
            <div class="row">
                <label
                    for="invoice-fy">
                    <?php echo I18n::__('invoice_label_sel_fy') ?>
                </label>
                <input
                    id="invoice-fy"
                    type="text"
                    name="dialog[fy]"
                    value="<?php echo htmlspecialchars($_SESSION['booking']['fy']) ?>"
                    required="required" />
            </div>
            <div class="row">
                <label
                    for="invoice-lo">
                    <?php echo I18n::__('invoice_label_sel_lo') ?>
                </label>
                <input
                    id="invoice-lo"
                    type="text"
                    name="dialog[lo]"
                    value="<?php echo htmlspecialchars($_SESSION['booking']['lo']) ?>"
                    required="required" />
            </div>
            <div class="row">
                <label
                    for="invoice-hi">
                    <?php echo I18n::__('invoice_label_sel_hi') ?>
                </label>
                <input
                    id="invoice-hi"
                    type="text"
                    name="dialog[hi]"
                    value="<?php echo htmlspecialchars($_SESSION['booking']['hi']) ?>"
                    required="required" />
            </div>
            <div class="buttons">
                <input
                    type="submit"
                    name="submit"
                    accesskey="s"
                    value="<?php echo I18n::__('invoice_sel_submit') ?>" />
            </div>
        </fielset>
        
        <!-- form details -->
        <fieldset
            class="tab">
            <legend class="verbose"><?php echo I18n::__('invoice_history_legend') ?></legend>
            

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
                    <label><?php echo I18n::__('invoice_label_name') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('invoice_label_dateofslaughter') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('invoice_label_person_account') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('invoice_label_person_nickname') ?></label>
                </div>
                <div class="span3">
                    <label><?php echo I18n::__('invoice_label_person_id') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('invoice_label_totalgros') ?></label>
                </div>
                <div class="span2">
                    &nbsp;
                </div>
            </div>
                <?php endif ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('invoice_history_item_legend') ?></legend>
                    <div
                        id="invoice-<?php echo $_record->getId() ?>"
                        class="row invoice-kind-<?php echo $_record->kind ?>">
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
                        <div class="span3">
                            <?php echo htmlspecialchars($_record->person->name) ?>
                        </div>
                        <div class="span2 number">
                            <?php echo htmlspecialchars($_record->decimal('totalgros', 2)) ?>
                        </div>
                        <div class="span1">
                            <button 
                                class="btn silent" 
                                type="button"
                                data-container="invoice-<?php echo $_record->getId() ?>"
                                data-href="<?php echo Url::build(sprintf('/booking/instructed/%d', $_record->getId())) ?>"><?php echo I18n::__('invoice_label_instructed_' . $_record->instructed) ?></button>
                        </div>
                    </div>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
    </form>
</article>
