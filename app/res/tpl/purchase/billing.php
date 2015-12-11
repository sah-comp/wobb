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
                    <label class="number"><?php echo I18n::__('invoice_label_name') ?></label>
                </div>
                <div class="span5">
                    <label><?php echo I18n::__('invoice_label_action') ?></label>
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
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][invoice][type]"
                        value="invoice" />
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][invoice][id]"
                        value="<?php echo $_deliverer->invoice()->getId() ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownDeliverer][<?php echo $_id ?>][totalnet]"
                        value="<?php echo htmlspecialchars($_deliverer->decimal('totalnet', 2)) ?>" />
                </div>
                <div class="row">
                    <div class="span3">
                            <?php echo htmlspecialchars($_deliverer->person->name) ?>
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
                            class="number"
                            disabled="disabled"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][invoice][name]"
                            value="<?php echo ($_deliverer->wasBilled()) ? htmlspecialchars($_deliverer->invoice()->name) : I18n::__('deliverer_not_yet_billed')  ?>"
                        />
                    </div>
                    <div class="span5">
                        <ul class="action">
                            <li>
                                <a class="pdf" href="<?php echo Url::build('/deliverer/internal/' . $_deliverer->getId()) ?>"><?php echo I18n::__('invoice_link_internal') ?></a>
                            </li>
                            <li>
                                <a class="pdf" href="<?php echo Url::build('/deliverer/dealer/' . $_deliverer->getId()) ?>"><?php echo I18n::__('invoice_link_dealer') ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- put subinfo here -->
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Billing buttons -->
        <div class="buttons"> 
            <?php if ($record->wasBilled()): ?>
                <a
                    href="<?php echo Url::build(sprintf("/statistic/?ref=%d", $record->getId())) ?>"
                    class="btn">
                    <?php echo I18n::__('purchase_href_goto_statistic') ?>
                </a>
            <?php endif ?>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('purchase_submit_billing') ?>" />
        </div>
        <!-- End of Billing buttons -->
    </form>
</article>
