<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("piggery_h1") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-piggery"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">

        <!-- form details -->
        <?php Flight::render('model/piggery/edit', array('record' => $record)); ?>
        <!-- end of form details -->

        <!-- Analysis buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('piggery_submit_add') ?>" />
        </div>
        <!-- End of piggery buttons -->
    </form>
</article>
