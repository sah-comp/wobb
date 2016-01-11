<div class="span1">
    <?php echo htmlspecialchars($record->name) ?>
</div>
<div class="span2">
    <?php echo htmlspecialchars($record->localizedDate('bookingdate')) ?>
</div>
<div class="span1">
    <?php echo htmlspecialchars($record->person->account) ?>
</div>
<div class="span1">
    <?php echo htmlspecialchars($record->person->nickname) ?>
</div>
<div class="span3">
    <?php echo htmlspecialchars($record->person->name) ?>
</div>
<div class="span2 number">
    <?php echo htmlspecialchars($record->decimal('totalgros', 2)) ?>
</div>
<div class="span1">
    <button 
        class="btn silent" 
        type="button"
        data-container="invoice-<?php echo $record->getId() ?>"
        data-href="<?php echo Url::build(sprintf('/invoice/payment/%d', $record->getId())) ?>"><?php echo I18n::__('invoice_label_paid_' . $record->paid) ?></button>
</div>
<div class="span1 number">
    <a
        href="<?php echo Url::build(sprintf('/invoice/cancel/%d', $record->getId())) ?>"
        class="ir delete ask"
        data-question="<?php echo I18n::__('invoice_confirm_cancel', null, array($record->name)) ?>"
        title="<?php echo I18n::__('invoice_title_cancel') ?>"><?php echo I18n::__('invoice_link_cancel') ?></a>
</div>
