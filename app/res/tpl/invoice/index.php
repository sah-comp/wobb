<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("invoice_h1_index") ?></h1>
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
                    <label><?php echo I18n::__('invoice_label_bookingdate') ?></label>
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
                    <label><?php echo I18n::__('invoice_label_paid') ?></label>
                </div>
            </div>
                <?php endif ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('invoice_history_item_legend') ?></legend>
                    <div
                        id="invoice-<?php echo $_record->getId() ?>"
                        class="row">
                        <div class="span1">
                            <?php echo htmlspecialchars($_record->name) ?>
                        </div>
                        <div class="span2">
                            <?php echo htmlspecialchars($_record->localizedDate('bookingdate')) ?>
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
                        <div class="span2">
                            <button class="btn" type="button"><?php echo I18n::__('invoice_label_paid_' . $_record->paid) ?></button>
                        </div>
                    </div>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Invoice buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('invoice_submit') ?>" />
        </div>
        <!-- End of Invoice buttons -->
    </form>
</article>
