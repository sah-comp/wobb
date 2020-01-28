<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo I18n::__("planning_h1_edit") ?></h1>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <form
        id="form-planning"
        class="panel"
        method="POST"
        accept-charset="utf-8"
        enctype="multipart/form-data">
        
        <!-- form details -->
        <?php Flight::render('model/plan/edit', array('record' => $record)); ?>
        <!-- end of form details -->
        
        <!-- Planning buttons -->
        <div class="buttons">
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('planning_submit_edit') ?>" />
        </div>
        <!-- End of Planning buttons -->
    </form>
</article>
