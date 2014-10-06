<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("statistic_h1") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-statistic"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <?php Flight::render('model/lanuv/edit', array('record' => $record)); ?>
        <!-- end of form details -->
        
        <!-- Statistic buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('statistic_submit_add') ?>" />
        </div>
        <!-- End of Statistic buttons -->
    </form>
</article>
