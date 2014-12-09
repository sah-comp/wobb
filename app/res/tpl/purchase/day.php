<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $record->getHeadline() ?></h1>
        <h2 class="date-slaughter"><?php echo $record->getDateOfSlaughter() ?></h2>
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
        <div>
            <input type="hidden" name="dialog[type]" value="csb" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('purchase_deliverer_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span4">
                    <label><?php echo I18n::__('deliverer_label_name') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_piggery') ?></label>
                </div>
                <div class="span3">
                    <label class="number"><?php echo I18n::__('deliverer_label_sprice') ?></label>
                </div>
                <div class="span3">
                    <label class="number"><?php echo I18n::__('deliverer_label_dprice') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            <?php foreach ($record->with(' ORDER BY supplier, earmark ')->ownDeliverer as $_id => $_deliverer): ?>
            <fieldset>
                <legend class="verbose"><?php echo I18n::__('purchase_deliverer_sub_legend') ?></legend>
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][type]"
                        value="deliverer" />
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][id]"
                        value="<?php echo $_id ?>" />
                </div>
                <div class="row">
                    <div class="span4">
                            <a 
                                href="#toggle"
                                class="toggle"
                                data-target="deliverer-<?php echo $_deliverer->getId() ?>-subdeliverer"
                                title="<?php echo I18n::__('deliverer_toggle_subdeliverer') ?>">
                                <?php echo htmlspecialchars($_deliverer->person->name) ?>
                            </a>
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_deliverer->piggery) ?>"
                            disabled="disabled"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('sprice', 3)) ?>"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                        />
                    </div>
                </div>
                <div
                    id="deliverer-<?php echo $_deliverer->getId() ?>-subdeliverer"
                    class="subdeliverer-container">
                <?php foreach ($_deliverer->with(' ORDER BY earmark ')->ownDeliverer as $_sub_id => $_sub): ?>
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][type]"
                        value="deliverer" />
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][id]"
                        value="<?php echo $_sub_id ?>" />
                </div>
                <div class="row">
                    <div class="span4">
                        <span class="subdeliverer-earmark">
                            <?php echo htmlspecialchars($_sub->earmark) ?>
                        </span>
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_sub->piggery) ?>"
                            disabled="disabled"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_sub->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('sprice', 3)) ?>"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_sub->decimal('dprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                        />
                    </div>
                </div>
                <?php endforeach ?>
                </div>
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Purchase buttons -->
        <div class="buttons">
            <a
                class="btn"
                href="<?php echo Url::build(sprintf("/purchase/calculation/%d", $record->getId())) ?>">
                <?php echo I18n::__('purchase_href_goto_calculation') ?>
            </a>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('purchase_submit_day') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
