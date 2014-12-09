<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__('purchase_damage_h1') ?></h1>
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
            <legend class="verbose"><?php echo I18n::__('purchase_damage_legend') ?></legend>
            
            <!-- row with labels -->
            <div class="row">
                <div class="span1">
                    <label><?php echo I18n::__('damage_label_name') ?></label>
                </div>
                <div class="span7">
                    <label><?php echo I18n::__('damage_label_desc') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('damage_label_sprice') ?></label>
                </div>
                <div class="span2">
                    <label class="number"><?php echo I18n::__('damage_label_dprice') ?></label>
                </div>
            </div>
            <!-- end of row with labels -->
            
            <?php foreach ($record->getDamagedaily() as $_id => $_daily): ?>
            <fieldset
                class="damagedaily <?php echo $record->hasDamageCode($_daily->name) ? 'found' : 'none' ?>">
                <legend class="verbose"><?php echo I18n::__('purchase_damage_sub_legend') ?></legend>
                
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][type]"
                        value="damagedaily" />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][id]"
                        value="<?php echo $_id ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][supplier]"
                        value="<?php echo htmlspecialchars($_daily->supplier) ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][condition]"
                        value="<?php echo htmlspecialchars($_daily->condition) ?>" />
                </div>
                <div class="row">

                    <div class="span1">
                        <input
                            type="text"
                            class="text"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][name]"
                            value="<?php echo htmlspecialchars($_daily->name) ?>"
                        />
                    </div>
                    <div class="span3">
                        <input
                            type="text"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][desc]"
                            value="<?php echo htmlspecialchars($_daily->desc) ?>"
                        />
                    </div>
                    <div class="span4">
                        <input
                            type="text"
                            class="number"
                            name="void[]"
                            value="<?php echo I18n::__('damage_condition_' . $_daily->condition) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_daily->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_daily->decimal('sprice', 3)) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_daily->decimal('dprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_daily->decimal('dprice', 3)) ?>"
                        />
                    </div>
                </div>
                
                <?php if ($record->hasDamageCode($_daily->name) ): ?>
                    <?php foreach ($_daily->ownDamagedaily as $_daily_stock_id => $_daily_stock): ?>
                        
                <div>
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][type]"
                        value="damagedaily" />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][id]"
                        value="<?php echo $_daily_stock_id ?>" />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][name]"
                        value="<?php echo htmlspecialchars($_daily_stock->name) ?>"
                    />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][desc]"
                        value="<?php echo htmlspecialchars($_daily_stock->desc) ?>"
                    />
                    <input
                        type="hidden"
                        name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][condition]"
                        value="<?php echo htmlspecialchars($_daily_stock->condition) ?>"
                    />
                </div>


                <div class="row">                        
                    <div class="span1">
                        <input
                            type="text"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][supplier]"
                            value="<?php echo htmlspecialchars($_daily_stock->supplier) ?>" />
                    </div>

                    <div class="span7">
                        <input
                            type="text"
                            class="number"
                            name="void[]"
                            value="<?php echo htmlspecialchars($_daily_stock->getSupplier()->name) ?>" />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][sprice]"
                            value="<?php echo htmlspecialchars($_daily_stock->decimal('sprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_daily_stock->decimal('sprice', 3)) ?>"
                        />
                    </div>
                    <div class="span2">
                        <input
                            type="text"
                            class="number"
                            name="dialog[ownDamagedaily][<?php echo $_id ?>][ownDamagedaily][<?php echo $_daily_stock_id ?>][dprice]"
                            value="<?php echo htmlspecialchars($_daily_stock->decimal('dprice', 3)) ?>"
                            placeholder="<?php echo htmlspecialchars($_daily_stock->decimal('dprice', 3)) ?>"
                        />
                    </div>
                </div>
                    <?php endforeach ?>
                <?php endif ?>
                
            </fieldset>
            <?php endforeach ?>
        </fieldset>
        <!-- end of form details -->
        
        <!-- Purchase buttons -->
        <div class="buttons">
            <a
                href="<?php echo Url::build(sprintf("/purchase/calculation/%d", $record->getId())) ?>"
                class="btn">
                <?php echo I18n::__('calculation_href_goto_calculation') ?>
            </a>
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('purchase_submit_damage') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
