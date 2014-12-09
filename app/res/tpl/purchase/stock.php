<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('purchase_stock_h1') ?></h1>
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
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('purchase_stock_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span4">
                    <label><?php echo I18n::__('stock_label_supplier') ?></label>
                </div>
                <div class="span2">
                    <label><?php echo I18n::__('stock_label_name') ?></label>
                </div>
                <div class="span1">
                    <label><?php echo I18n::__('stock_label_quality') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('stock_label_weight') ?></label>
                </div>
                <div class="span1">
                    <label class="number"><?php echo I18n::__('stock_label_mfa') ?></label>
                </div>
                <div class="span3">
                    <label><?php echo I18n::__('stock_label_damage1') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($records as $_stock_id => $_stock): ?>
                
            <div>                
                <input type="hidden" name="dialog[stock][<?php echo $_stock_id ?>][type]" value="stock" />
                <input type="hidden" name="dialog[stock][<?php echo $_stock_id ?>][id]" value="<?php echo $_stock->getId() ?>" />
                <input
                    type="hidden"
                    name="dialog[stock][<?php echo $_stock_id ?>][supplier]"
                    value="<?php echo htmlspecialchars($_stock->supplier) ?>" />
            </div>
                
            <div class="row">
                
                <div class="span4">
                    <?php echo htmlspecialchars($_stock->getPersonBySupplier()->name) ?>
                    <div class="deliverer-info">
                        <?php echo $_stock->supplier . ', ' . $_stock->earmark ?>
                    </div>
                </div>
                
                <div class="span2">
                    <input
                        type="text"
                        name="dialog[stock][<?php echo $_stock_id ?>][name]"
                        value="<?php echo htmlspecialchars($_stock->name) ?>"
                        required="required" />
                </div>
                
                <div class="span1">
                    <input
                        type="text"
                        name="dialog[stock][<?php echo $_stock_id ?>][quality]"
                        value="<?php echo htmlspecialchars($_stock->quality) ?>"
                        required="required" />
                </div>
                
                <div class="span1">
                    <input
                        type="text"
                        class="number"
                        name="dialog[stock][<?php echo $_stock_id ?>][weight]"
                        value="<?php echo htmlspecialchars($_stock->decimal('weight', 2)) ?>"
                        required="required" />
                </div>
                
                <div class="span1">
                    <input
                        type="text"
                        class="number"
                        name="dialog[stock][<?php echo $_stock_id ?>][mfa]"
                        value="<?php echo htmlspecialchars($_stock->decimal('mfa', 2)) ?>"
                        required="required" />
                </div>
                
                <div class="span3">
                    <select
                        name="dialog[stock][<?php echo $_stock_id ?>][damage1]">
                        <option value=""><?php echo I18n::__('damage1_select_one_or_empty') ?></option>
                        <?php foreach (R::find('var', " supplier = '' AND kind = 'damage1' ORDER BY name ") as $_damage_id => $_damage): ?>
                            <option
                                value="<?php echo htmlspecialchars($_damage->name) ?>"
                                <?php echo ($_stock->damage1 == $_damage->name) ? 'selected="selected"' : '' ?>>
                                <?php echo htmlspecialchars($_damage->name . ' ' . $_damage->note) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>
                
            <?php endforeach ?>
            
        </fieldset>
        <!-- end of form details -->
        
        <!-- Purchase buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('purchase_submit_stock') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
