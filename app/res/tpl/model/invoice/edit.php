<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<!-- invoice edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('invoice_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="invoice-company">
            <?php echo I18n::__('invoice_label_company') ?>
        </label>
        <select
            id="invoice-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' ORDER BY name') as $_id => $_company): ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('kind')) ? 'error' : ''; ?>">
        <label
            for="invoice-kind">
            <?php echo I18n::__('invoice_label_kind') ?>
        </label>
        <select
            id="invoice-kind"
            name="dialog[kind]">
            <?php foreach ($record->getKinds() as $_kind): ?>
            <option
                value="<?php echo $_kind ?>"
                <?php echo ($record->kind == $_kind) ? 'selected="selected"' : '' ?>><?php echo I18n::__('invoice_label_kind_' . $_kind) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('fy')) ? 'error' : ''; ?>">
        <label
            for="invoice-fy">
            <?php echo I18n::__('invoice_label_fy') ?>
        </label>
        <input
            id="invoice-fy"
            type="text"
            name="dialog[fy]"
            value="<?php echo htmlspecialchars($record->fy) ?>"
            required="required"
            readonly="readonly" />
    </div>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="invoice-name">
            <?php echo I18n::__('invoice_label_name') ?>
        </label>
        <input
            id="invoice-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required"
            readonly="readonly" />
    </div>
    <div class="row <?php echo ($record->hasError('bookingdate')) ? 'error' : ''; ?>">
        <label
            for="invoice-bookingdate">
            <?php echo I18n::__('invoice_label_bookingdate') ?>
        </label>
        <input
            id="invoice-bookingdate"
            type="date"
			placeholder="yyyy-mm-dd"
            name="dialog[bookingdate]"
            value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($record->bookingdate))) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('dateofslaughter')) ? 'error' : ''; ?>">
        <label
            for="invoice-dateofslaughter">
            <?php echo I18n::__('invoice_label_dateofslaughter') ?>
        </label>
        <input
            id="invoice-dateofslaughter"
            type="date"
			placeholder="yyyy-mm-dd"
            name="dialog[dateofslaughter]"
            value="<?php echo htmlspecialchars($record->dateofslaughter) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('person_id')) ? 'error' : ''; ?>">
        <label
            for="invoice-person">
            <?php echo I18n::__('invoice_label_person') ?>
        </label>
        <select
            id="invoice-person"
            name="dialog[person_id]">
            <?php foreach (R::find('person', ' ORDER BY name') as $_id => $_person): ?>
            <option
                value="<?php echo $_person->getId() ?>"
                <?php echo ($record->person_id == $_person->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_person->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('totalnet')) ? 'error' : ''; ?>">
        <label
            for="invoice-totalnet">
            <?php echo I18n::__('invoice_label_totalnet') ?>
        </label>
        <input
            id="invoice-totalnet"
            type="text"
            name="dialog[totalnet]"
            value="<?php echo htmlspecialchars($record->decimal('totalnet', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('bonusnet')) ? 'error' : ''; ?>">
        <label
            for="invoice-bonusnet">
            <?php echo I18n::__('invoice_label_bonusnet') ?>
        </label>
        <input
            id="invoice-bonusnet"
            type="text"
            name="dialog[bonusnet]"
            value="<?php echo htmlspecialchars($record->decimal('bonusnet', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('costnet')) ? 'error' : ''; ?>">
        <label
            for="invoice-costnet">
            <?php echo I18n::__('invoice_label_costnet') ?>
        </label>
        <input
            id="invoice-costnet"
            type="text"
            name="dialog[costnet]"
            value="<?php echo htmlspecialchars($record->decimal('costnet', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('subtotalnet')) ? 'error' : ''; ?>">
        <label
            for="invoice-subtotalnet">
            <?php echo I18n::__('invoice_label_subtotalnet') ?>
        </label>
        <input
            id="invoice-subtotalnet"
            type="text"
            name="dialog[subtotalnet]"
            value="<?php echo htmlspecialchars($record->decimal('subtotalnet', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('vat_id')) ? 'error' : ''; ?>">
        <label
            for="invoice-vat">
            <?php echo I18n::__('invoice_label_vat') ?>
        </label>
        <select
            id="invoice-vat"
            name="dialog[vat_id]">
            <?php foreach (R::find('vat', ' ORDER BY name') as $_id => $_vat): ?>
            <option
                value="<?php echo $_vat->getId() ?>"
                <?php echo ($record->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('vatvalue')) ? 'error' : ''; ?>">
        <label
            for="invoice-vatvalue">
            <?php echo I18n::__('invoice_label_vatvalue') ?>
        </label>
        <input
            id="invoice-vatvalue"
            type="text"
            name="dialog[vatvalue]"
            value="<?php echo htmlspecialchars($record->decimal('vatvalue', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('totalgros')) ? 'error' : ''; ?>">
        <label
            for="invoice-totalgros">
            <?php echo I18n::__('invoice_label_totalgros') ?>
        </label>
        <input
            id="invoice-totalgros"
            type="text"
            name="dialog[totalgros]"
            value="<?php echo htmlspecialchars($record->decimal('totalgros', 3)) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('duedate')) ? 'error' : ''; ?>">
        <label
            for="invoice-duedate">
            <?php echo I18n::__('invoice_label_duedate') ?>
        </label>
        <input
            id="invoice-duedate"
            type="date"
			placeholder="yyyy-mm-dd"
            name="dialog[duedate]"
            value="<?php echo htmlspecialchars($record->duedate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('paid')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[paid]"
            value="0" />
        <input
            id="invoice-paid"
            type="checkbox"
            name="dialog[paid]"
            <?php echo ($record->paid) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="invoice-paid"
            class="cb">
            <?php echo I18n::__('invoice_label_paid') ?>
        </label>
    </div>
</fieldset>
<!-- end of invoice edit form -->