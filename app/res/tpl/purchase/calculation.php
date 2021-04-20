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
                <div class="span3">
                    <label><?php echo I18n::__('deliverer_label_name') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('deliverer_label_piggery') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('deliverer_label_sprice') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('deliverer_label_dprice') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_totalnet') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('deliverer_label_invoice') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('deliverer_label_pdf') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            <?php
                /**
                 * Reset counters for specialprice and specialprice->ownCost.
                 */
                $_n = 0;
                $_m = 0;
            ?>
            <?php foreach ($record->with(' ORDER BY supplier, earmark ')->ownDeliverer as $_id => $_deliverer): ?>
            <fieldset id="deli-<?php echo $_deliverer->getId() ?>">
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
                        name="dialog[ownDeliverer][<?php echo $_id ?>][enabled]"
                        value="1" />
                </div>
                <div class="row">
                    <div class="span3 cutoff">
                        <a
                            href="#toggle"
                            class="toggle supplier-name"
                            data-target="deliverer-<?php echo $_deliverer->getId() ?>-subdeliverer"
                            title="<?php echo I18n::__('deliverer_toggle_subdeliverer') ?>">
                            <?php echo htmlspecialchars($_deliverer->person->name) ?>
                        </a>
                        <div class="deliverer-info">
                            <?php echo $_deliverer->getInformation() ?>
                        </div>
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
                    <div class="span1">
                        <input
                            type="text"
                            class="number <?php echo ($_deliverer->hasService()) ? '' : 'invisible' ?>"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number <?php echo ($_deliverer->getInfoAboutDealerPrice()) ? 'alarm' : '' ?>"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                            required="required"
                            title="<?php echo htmlspecialchars($_deliverer->getInfoAboutDealerPrice()) ?>"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][totalnet]"
                            value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_deliverer->decimal('totalnet', 2)) : I18n::__('deliverer_not_yet_calculated')  ?>"

                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="stash_invoice_name"
                            value="<?php echo ($_deliverer->wasBilled()) ? htmlspecialchars($_deliverer->invoice->name) : I18n::__('deliverer_not_yet_billed')  ?>"

                        />

                    </div>
                    <div class="span2">
                        <?php if ($_deliverer->wasBilled()): ?>
                        <ul class="action">
                            <li>
                                <a
                                    class="ir voucher-internal <?php echo $_deliverer->genPdfInternal() ?>"
                                    title="<?php echo I18n::__('invoice_link_internal_title') ?>"
                                    href="<?php echo Url::build('/deliverer/internal/' . $_deliverer->getId()) ?>"><?php echo I18n::__('invoice_link_internal') ?></a>
                            </li>
                            <li>
                                <a
                                    class="ir voucher-dealer <?php echo $_deliverer->person->billingtransport ?>  <?php echo $_deliverer->genPdfDealer() ?>"
                                    title="<?php echo I18n::__('invoice_link_dealer_title') ?>"
                                    href="<?php echo Url::build('/deliverer/dealer/' . $_deliverer->getId()) ?>"><?php echo I18n::__('invoice_link_dealer') ?></a>
                            </li>
                            <?php if ($_deliverer->wantsInvoiceAsEmail()): ?>
                            <li>
                                <a
                                    class="ir voucher-dealer-mail <?php echo $_deliverer->wasSent() ?>"
                                    title="<?php echo I18n::__('invoice_link_mail_title') ?>"
                                    href="<?php echo Url::build('/deliverer/mail/' . $_deliverer->getId()) ?>"><?php echo I18n::__('invoice_link_mail') ?></a>
                            </li>
                            <?php endif ?>
                        </ul>
                        <?php else: ?>
                        &nbsp;
                        <?php endif ?>
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
                    <div class="span3">
                        <span class="subdeliverer-earmark">
                            <?php echo htmlspecialchars($_sub->earmark) ?>
                        </span>
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][piggery]"
                            value="<?php echo htmlspecialchars($_sub->piggery) ?>"

                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number <?php echo ($_deliverer->hasService()) ? '' : 'invisible' ?>"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_sub->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('sprice', 3)) ?>"
                        />
                    </div>
                    <div class="span1">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_sub->decimal('dprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_deliverer->decimal('dprice', 3)) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            readonly="readonly"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownDeliverer][<?php echo $_sub_id ?>][totalnet]"
                            value="<?php echo ($_deliverer->wasCalculated()) ? htmlspecialchars($_sub->decimal('totalnet', 2)) : I18n::__('deliverer_not_yet_calculated')  ?>"

                        />
                    </div>
                </div>


                <?php endforeach ?>

                <?php if ($_sprices = $_deliverer->getSpecialPrices()): ?>
                <div>
                    <?php foreach ($_sprices as $_sprice_id => $_sprice): ?>
                    <?php $_n++; ?>
                    <div>
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][type]"
                            value="specialprice" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][id]"
                            value="<?php echo $_sprice->getId() ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][note]"
                            value="<?php echo htmlspecialchars($_sprice->note) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][name]"
                            value="<?php echo htmlspecialchars($_sprice->name) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][condition]"
                            value="<?php echo htmlspecialchars($_sprice->condition) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][kind]"
                            value="<?php echo htmlspecialchars($_sprice->kind) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][doesnotaffectlanuv]"
                            value="<?php echo htmlspecialchars($_sprice->doesnotaffectlanuv) ?>" />
                    </div>

                    <div class="row">
                        <div class="span3">
                            <small><?php echo htmlspecialchars($_sprice->note) ?></small>
                        </div>
                        <div class="span1">
                            <input
                                type="text"
                                class="number"
                                readonly="readonly"
                                name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][piggery]"
                                value="<?php echo htmlspecialchars($_sprice->piggery) ?>"
                            />
                        </div>
                        <div class="span1">
                            <input
                                type="text"
                                class="number <?php echo ($_deliverer->hasService()) ? '' : 'invisible' ?>"
                                name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][sprice]"
                                value="<?php echo htmlspecialchars($_sprice->decimal('sprice', 3)) ?>"
                                placeholder="<?php echo htmlspecialchars($_sprice->decimal('sprice', 3)) ?>"
                            />
                        </div>
                        <div class="span1">
                            <input
                                type="text"
                                class="number"
                                name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][dprice]"
                                value="<?php echo htmlspecialchars($_sprice->decimal('dprice', 3)) ?>"
                                placeholder="<?php echo htmlspecialchars($_sprice->decimal('dprice', 3)) ?>"
                            />
                        </div>
                    </div>
                    <?php foreach ($_sprice->ownScost as $_scost_id => $_scost): ?>
                    <?php $_m++ ?>
                    <div>
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][ownScost][<?php echo $_m ?>][type]"
                            value="scost" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][ownScost][<?php echo $_m ?>][id]"
                            value="<?php echo $_scost->getId() ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][ownScost][<?php echo $_m ?>][label]"
                            value="<?php echo htmlspecialchars($_scost->label) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][ownScost][<?php echo $_m ?>][content]"
                            value="<?php echo htmlspecialchars($_scost->content) ?>" />
                        <input
                            type="hidden"
                            name="dialog[ownDeliverer][<?php echo $_id ?>][ownSpecialprice][<?php echo $_n ?>][ownScost][<?php echo $_m ?>][value]"
                            value="<?php echo htmlspecialchars($_scost->value) ?>" />
                    </div>
                    <?php endforeach ?>
                    <?php endforeach ?>
                </div>
                <?php endif ?>

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
