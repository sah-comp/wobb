<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("purchase_h1_csb") ?></h1>
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
        <?php Flight::render('model/csb/edit', array('record' => $record)); ?>
        <!-- end of form details -->
        
        <!-- Purchase buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('purchase_submit_add') ?>" />
        </div>
        <!-- End of Purchase buttons -->
    </form>
</article>
