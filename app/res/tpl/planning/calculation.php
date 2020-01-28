<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $record->getHeadline() ?></h1>
        <h2 class="date-slaughter"><?php echo $record->getDateOfSlaughter() ?></h2>
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
        <div>
            <input type="hidden" name="dialog[type]" value="csb" />
            <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
        </div>
        <fieldset class="tab">
            <legend class="verbose"><?php echo I18n::__('planning_calculation_legend') ?></legend>
			<p>FORM</p>
		</fieldset>
        
        <!-- Planning buttons -->
        <div class="buttons"> 
            <input
                type="submit"
                name="submit"
                accesskey="s"
                value="<?php echo I18n::__('planning_submit_calculation') ?>" />
        </div>
        <!-- End of Planning buttons -->
    </form>
</article>
