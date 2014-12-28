<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $record->getHeadline('billing') ?></h1>
        <h2 class="date-slaughter"><?php echo $record->getDateOfSlaughter() ?></h2>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-billing"
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
                <div class="span3">
                    <label><?php echo I18n::__('deliverer_label_name') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('deliverer_label_enabled') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('deliverer_label_piggery') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_sprice') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_dprice') ?></label>
                </div>
                <div class="span3">
                    <label class="number"><?php echo I18n::__('deliverer_label_totalnet') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            <?php $_n = 0 ?>
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
                    <div class="span3">
                        <a 
                            href="#toggle"
                            class="toggle"
                            data-target="deliverer-<?php echo $_deliverer->getId() ?>-cost"
                            title="<?php echo I18n::__('deliverer_toggle_cost') ?>">
                            <?php echo htmlspecialchars($_deliverer->person->name) ?>
                        </a>
                        <div class="deliverer-info">
                            <?php echo $_deliverer->getInformation() ?>
                        </div>
                    </div>
                    <div class="span1">
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][enabled]"
                            value="0" />
                        <input
                            type="checkbox"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][enabled]"
                            <?php echo ($_deliverer->enabled) ? 'checked="checked"' : '' ?>
                            value="1" />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_deliverer->piggery) ?>"
                            
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number <?php echo ( $_deliverer->hasService() ) ? '' : 'invisible' ?>"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                            required="required"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][totalnet]"
                            value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('totalnet', 3)) : I18n::__('deliverer_not_yet_calculated')  ?>"
                            
                        />
                        
                    </div>
                </div>
                
                <div
                    id="deliverer-<?php echo $_deliverer->getId() ?>-cost"
                    class="subdeliverer-container">
                    <p>Meine Kosten und Steuersatz etc.</p>
                </div>
                
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Billing buttons -->
        <div class="buttons"> 
            <?php if ($record->wasBilled()): ?>
                <a
                    href="<?php echo Url::build(sprintf("/report/index/%d", $record->getId())) ?>"
                    class="btn">
                    <?php echo I18n::__('billing_href_goto_report') ?>
                </a>
            <?php endif ?>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('billing_submit_index') ?>" />
        </div>
        <!-- End of Billing buttons -->
    </form>
</article>
