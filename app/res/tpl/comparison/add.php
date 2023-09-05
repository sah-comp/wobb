<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("comparison_h1_csb") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-comparison"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <?php Flight::render('model/comparison/edit', array('record' => $record)); ?>
        <!-- end of form details -->
        
        <!-- comparison buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('comparison_submit_add') ?>" />
        </div>
        <!-- End of comparison buttons -->
    </form>
</article>
