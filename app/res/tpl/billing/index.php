<article class="main">
    <header id="header-toolbar" class="fixable">
        <h1><?php echo $record->getHeadline('billing') ?></h1>
        <h2 class="date-slaughter"><?php echo $record->getDateOfSlaughter() ?></h2>
        <nav>
            <?php echo $toolbar ?>
        </nav>
    </header>
    <section>
        <?php echo I18n::__('not_yet_implemented') ?>
    </section>
</article>
