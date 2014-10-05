<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $record->getHeadline() ?></h1>
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
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('purchase_deliverer_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span1">
                    &nbsp;
                </div>
                <div class="span5">
                    <label><?php echo I18n::__('deliverer_label_name') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_piggery') ?></label>
                </div>
                <div class="span4">
                    <label class="number"><?php echo I18n::__('deliverer_label_totalnet') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($record->with(' ORDER BY supplier, earmark ')->ownDeliverer as $_id => $_deliverer): ?>
            <div>
                <input type="hidden" name="dialog[type]" value="csb" />
                <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
            </div>
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
                    <div class="span1">
                        <span class="fn pday-supplier"><?php echo htmlspecialchars($_deliverer->supplier) ?></span>
                    </div>
                    <div class="span5">
                        <b><?php echo htmlspecialchars($_deliverer->person->name) ?></b>
                    </div>
                    <div class="span2">
                        <input
                            type="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_deliverer->piggery) ?>"
                            disabled="disabled"
                        />
                    </div>
                    <div class="span4">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][totalnet]"
                            value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('totalnet', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                            disabled="disabled"
                        />
                        
                    </div>
                </div>
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
                value="<?php echo I18n::__('purchase_submit_calculation') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
