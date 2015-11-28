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
<!-- person edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <?php if ($record->email): ?>
    <img
    	src="<?php echo Gravatar::src($record->email, 72) ?>"
    	class="gravatar-account circular no-shadow"
    	width="72"
    	height="72"
    	alt="<?php echo htmlspecialchars($record->name) ?>" />
    <?php endif ?>
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend') ?></legend>
    <!-- grid based header -->
    <div class="row nomargins">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <label
                for="person-nickname"
                class="<?php echo ($record->hasError('nickname')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_nickname') ?>
            </label>
        </div>
        <div class="span2">
            <label
                for="person-language"
                class="<?php echo ($record->hasError('language')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_language') ?>
            </label>
        </div>
        <div class="span4">
            <label
                for="person-account"
                class="<?php echo ($record->hasError('account')) ? 'error' : ''; ?>">
                <?php echo I18n::__('person_label_account') ?>
            </label>
        </div>
    </div>
    <!-- end of grid based header -->
    <!-- grid based data -->
    <div class="row">
        <div class="span3">&nbsp;</div>
        <div class="span3">
            <input
                id="person-nickname"
                class="autowidth"
                type="text"
                name="dialog[nickname]"
                placeholder="<?php echo I18n::__('person_placeholder_nickname') ?>"
                value="<?php echo htmlspecialchars($record->nickname) ?>"
                required="required" />
        </div>
        <div class="span2">
            <select
                id="person-language"
                name="dialog[language]">
                <?php foreach (R::findAll('language') as $_lang_id => $_lang): ?>
                <option
                    value="<?php echo $_lang->iso ?>"
                    <?php echo ($record->language == $_lang->iso) ? 'selected="selected"' : '' ?>>
                    <?php echo htmlspecialchars($_lang->name) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="span4">
            <input
                id="person-account"
                class="autowidth"
                type="text"
                name="dialog[account]"
                value="<?php echo htmlspecialchars($record->account) ?>" />
        </div>
    </div>
    <div class="row <?php echo ($record->hasError('enabled')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[enabled]"
            value="0" />
        <input
            id="person-enabled"
            type="checkbox"
            name="dialog[enabled]"
            <?php echo ($record->enabled) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="person-enabled"
            class="cb">
            <?php echo I18n::__('person_label_enabled') ?>
        </label>
    </div>
    <!-- end of grid based data -->
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_wobb') ?></legend>
    <div class="row <?php echo ($record->hasError('pricing_id')) ? 'error' : ''; ?>">
        <label
            for="person-pricing">
            <?php echo I18n::__('person_label_pricing') ?>
        </label>
        <select
            id="person-pricing"
            name="dialog[pricing_id]">
            <option value=""><?php echo I18n::__('person_pricing_please_select') ?></option>
            <?php foreach (R::find('pricing', ' active = 1 ORDER BY name') as $_id => $_pricing): ?>
            <option
                value="<?php echo $_pricing->getId() ?>"
                <?php echo ($record->pricing_id == $_pricing->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_pricing->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('vat_id')) ? 'error' : ''; ?>">
        <label
            for="person-vat">
            <?php echo I18n::__('person_label_vat') ?>
        </label>
        <select
            id="person-vat"
            name="dialog[vat_id]">
            <option value=""><?php echo I18n::__('person_vat_please_select') ?></option>
            <?php foreach (R::find('vat', ' ORDER BY name') as $_id => $_vat): ?>
            <option
                value="<?php echo $_vat->getId() ?>"
                <?php echo ($record->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_email') ?></legend>
    <div class="row <?php echo ($record->hasError('email')) ? 'error' : ''; ?>">
        <label
            for="person-email">
            <?php echo I18n::__('person_label_email') ?>
        </label>
        <input
            id="person-email"
            type="email"
            name="dialog[email]"
            value="<?php echo htmlspecialchars($record->email) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_name') ?></legend>
    <div class="row <?php echo ($record->hasError('attention')) ? 'error' : ''; ?>">
        <label
            for="person-attention">
            <?php echo I18n::__('person_label_attention') ?>
        </label>
        <input
            id="person-attention"
            type="text"
            name="dialog[attention]"
            value="<?php echo htmlspecialchars($record->attention) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('title')) ? 'error' : ''; ?>">
        <label
            for="person-title">
            <?php echo I18n::__('person_label_title') ?>
        </label>
        <input
            id="person-title"
            type="text"
            name="dialog[title]"
            value="<?php echo htmlspecialchars($record->title) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('firstname')) ? 'error' : ''; ?>">
        <label
            for="person-firstname">
            <?php echo I18n::__('person_label_firstname') ?>
        </label>
        <input
            id="person-firstname"
            type="text"
            name="dialog[firstname]"
            value="<?php echo htmlspecialchars($record->firstname) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('lastname')) ? 'error' : ''; ?>">
        <label
            for="person-lastname">
            <?php echo I18n::__('person_label_lastname') ?>
        </label>
        <input
            id="person-lastname"
            type="text"
            name="dialog[lastname]"
            value="<?php echo htmlspecialchars($record->lastname) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('suffix')) ? 'error' : ''; ?>">
        <label
            for="person-suffix">
            <?php echo I18n::__('person_label_suffix') ?>
        </label>
        <input
            id="person-suffix"
            type="text"
            name="dialog[suffix]"
            value="<?php echo htmlspecialchars($record->suffix) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_organization') ?></legend>
    <div class="row <?php echo ($record->hasError('organization')) ? 'error' : ''; ?>">
        <label
            for="person-organization">
            <?php echo I18n::__('person_label_organization') ?>
        </label>
        <textarea
            id="person-organization"
            name="dialog[organization]"
            rows="3"
            cols="60"><?php echo htmlspecialchars($record->organization) ?></textarea>
    </div>
    <div class="row <?php echo ($record->hasError('company')) ? 'error' : ''; ?>">
        <label
            for="person-company"
            class="cb">
            <?php echo I18n::__('person_label_company') ?>
        </label>
        <input
            type="hidden"
            name="dialog[company]"
            value="0" />
        <input
            id="person-company"
            type="checkbox"
            name="dialog[company]"
            <?php echo ($record->company) ? 'checked="checked"' : '' ?>
            value="1" />
    </div>
    <div class="row <?php echo ($record->hasError('jobtitle')) ? 'error' : ''; ?>">
        <label
            for="person-jobtitle">
            <?php echo I18n::__('person_label_jobtitle') ?>
        </label>
        <input
            id="person-jobtitle"
            type="text"
            name="dialog[jobtitle]"
            value="<?php echo htmlspecialchars($record->jobtitle) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('department')) ? 'error' : ''; ?>">
        <label
            for="person-department">
            <?php echo I18n::__('person_label_department') ?>
        </label>
        <input
            id="person-department"
            type="text"
            name="dialog[department]"
            value="<?php echo htmlspecialchars($record->department) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_phone') ?></legend>
    <div class="row <?php echo ($record->hasError('phone')) ? 'error' : ''; ?>">
        <label
            for="person-phone">
            <?php echo I18n::__('person_label_phone') ?>
        </label>
        <input
            id="person-phone"
            type="text"
            name="dialog[phone]"
            value="<?php echo htmlspecialchars($record->phone) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('fax')) ? 'error' : ''; ?>">
        <label
            for="person-fax">
            <?php echo I18n::__('person_label_fax') ?>
        </label>
        <input
            id="person-fax"
            type="text"
            name="dialog[fax]"
            value="<?php echo htmlspecialchars($record->fax) ?>" />
    </div>
</fieldset>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('person_legend_url') ?></legend>
    <div class="row <?php echo ($record->hasError('url')) ? 'error' : ''; ?>">
        <label
            for="person-url">
            <?php echo I18n::__('person_label_url') ?>
        </label>
        <input
            id="person-url"
            type="text"
            name="dialog[url]"
            value="<?php echo htmlspecialchars($record->url) ?>" />
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'person-tabs',
        'tabs' => array(
            'person-address' => I18n::__('person_address_tab'),
            'person-baseprice' => I18n::__('person_baseprice_tab'),
            'person-condition' => I18n::__('person_condition_tab'),
            'person-cost' => I18n::__('person_cost_tab'),
            'person-billing' => I18n::__('person_billing_tab'),
            'person-bankaccount' => I18n::__('person_bankaccount_tab'),
            'person-kidnap' => I18n::__('person_kidnap_tab')
        ),
        'default_tab' => 'person-address'
    )) ?>
    <fieldset
        id="person-address"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('person_legend_address_tab') ?></legend>
            <div
                id="person-<?php echo $record->getId() ?>-address-container"
                class="container attachable detachable sortable">
                <?php if (count($record->ownAddress) == 0) $record->ownAddress[] = R::dispense('address') ?>
                <?php $index = 0 ?>
                <?php foreach ($record->ownAddress as $_address_id => $_address): ?>
                <?php $index++ ?>
                <?php Flight::render('model/person/own/address', array(
                    'record' => $record,
                    '_address' => $_address,
                    'index' => $index
                )) ?>
                <?php endforeach ?>
            </div>
    </fieldset>
    <fieldset
        id="person-condition"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_condition') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-condition-container"
            class="container attachable detachable sortable">
            <?php if (count($record->ownCondition) == 0) $record->ownCondition[] = R::dispense('condition') ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownCondition as $_condition_id => $_condition): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/condition', array(
                'record' => $record,
                '_condition' => $_condition,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="person-cost"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_cost') ?></legend>
        <div
            id="person-<?php echo $record->getId() ?>-cost-container"
            class="container attachable detachable sortable">
            <?php if (count($record->ownCost) == 0) $record->ownCost[] = R::dispense('cost') ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownCost as $_cost_id => $_cost): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/cost', array(
                'record' => $record,
                '_cost' => $_cost,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
    <fieldset
        id="person-billing"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_billing_tab') ?></legend>
            <div class="row <?php echo ($record->hasError('hasservice')) ? 'error' : ''; ?>">
                <label
                    for="person-hasservice"
                    class="cb">
                    <?php echo I18n::__('person_label_hasservice') ?>
                </label>
                <input
                    type="hidden"
                    name="dialog[hasservice]"
                    value="0" />
                <input
                    id="person-hasservice"
                    type="checkbox"
                    name="dialog[hasservice]"
                    <?php echo ($record->hasservice) ? 'checked="checked"' : '' ?>
                    value="1" />
            </div>
            <div class="row <?php echo ($record->hasError('hasdealer')) ? 'error' : ''; ?>">
                <label
                    for="person-hasdealer"
                    class="cb">
                    <?php echo I18n::__('person_label_hasdealer') ?>
                </label>
                <input
                    type="hidden"
                    name="dialog[hasdealer]"
                    value="0" />
                <input
                    id="person-hasdealer"
                    type="checkbox"
                    name="dialog[hasdealer]"
                    <?php echo ($record->hasdealer) ? 'checked="checked"' : '' ?>
                    value="1" />
            </div>
            <div class="row <?php echo ($record->hasError('billingtransport')) ? 'error' : ''; ?>">
                <label
                    for="var-billingtransport">
                    <?php echo I18n::__('person_label_billingtransport') ?>
                </label>
                <select
                    id="person-billingtransport"
                    name="dialog[billingtransport]">
                    <?php foreach ($record->getBillingtransports() as $_kind): ?>
                    <option
                        value="<?php echo $_kind ?>"
                        <?php echo ($record->billingtransport == $_kind) ? 'selected="selected"' : '' ?>>
                        <?php echo I18n::__('person_billingtransport_'.$_kind) ?>
                    </option>
                    <?php endforeach ?>
                </select>
                <p class="info"><?php echo I18n::__('person_info_billingtransport') ?></p>
            </div>
    </fieldset>
    <fieldset
        id="person-baseprice"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_baseprice_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('relsprice')) ? 'error' : ''; ?>">
            <label
                for="person-relsprice">
                <?php echo I18n::__('person_label_relsprice') ?>
            </label>
            <input
                id="person-relsprice"
                type="text"
                class="number"
                name="dialog[relsprice]"
                value="<?php echo htmlspecialchars($record->decimal('relsprice', 3)) ?>" />
            <p class="info"><?php echo I18n::__('person_info_relsprice') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('reldprice')) ? 'error' : ''; ?>">
            <label
                for="person-reldprice">
                <?php echo I18n::__('person_label_reldprice') ?>
            </label>
            <input
                id="person-reldprice"
                type="text"
                class="number"
                name="dialog[reldprice]"
                value="<?php echo htmlspecialchars($record->decimal('reldprice', 3)) ?>" />
            <p class="info"><?php echo I18n::__('person_info_reldprice') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="person-bankaccount"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_bankaccount_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('bankname')) ? 'error' : ''; ?>">
            <label
                for="person-bankname">
                <?php echo I18n::__('person_label_bankname') ?>
            </label>
            <input
                id="person-bankname"
                type="text"
                name="dialog[bankname]"
                value="<?php echo htmlspecialchars($record->bankname) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankcode')) ? 'error' : ''; ?>">
            <label
                for="person-bankcode">
                <?php echo I18n::__('person_label_bankcode') ?>
            </label>
            <input
                id="person-bankcode"
                type="text"
                name="dialog[bankcode]"
                value="<?php echo htmlspecialchars($record->bankcode) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankaccount')) ? 'error' : ''; ?>">
            <label
                for="person-bankaccountfield">
                <?php echo I18n::__('person_label_bankaccount') ?>
            </label>
            <input
                id="person-bankaccountfield"
                type="text"
                name="dialog[bankaccount]"
                value="<?php echo htmlspecialchars($record->bankaccount) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bic')) ? 'error' : ''; ?>">
            <label
                for="person-bic">
                <?php echo I18n::__('person_label_bic') ?>
            </label>
            <input
                id="person-bic"
                type="text"
                name="dialog[bic]"
                value="<?php echo htmlspecialchars($record->bic) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('iban')) ? 'error' : ''; ?>">
            <label
                for="person-iban">
                <?php echo I18n::__('person_label_iban') ?>
            </label>
            <input
                id="person-iban"
                type="text"
                name="dialog[iban]"
                value="<?php echo htmlspecialchars($record->iban) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('taxoffice')) ? 'error' : ''; ?>">
            <label
                for="person-taxoffice">
                <?php echo I18n::__('person_label_taxoffice') ?>
            </label>
            <input
                id="person-taxoffice"
                type="text"
                name="dialog[taxoffice]"
                value="<?php echo htmlspecialchars($record->taxoffice) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('taxid')) ? 'error' : ''; ?>">
            <label
                for="person-taxid">
                <?php echo I18n::__('person_label_taxid') ?>
            </label>
            <input
                id="person-taxid"
                type="text"
                name="dialog[taxid]"
                value="<?php echo htmlspecialchars($record->taxid) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('vatid')) ? 'error' : ''; ?>">
            <label
                for="person-vatid">
                <?php echo I18n::__('person_label_vatid') ?>
            </label>
            <input
                id="person-vatid"
                type="text"
                name="dialog[vatid]"
                value="<?php echo htmlspecialchars($record->vatid) ?>" />
        </div>
    </fieldset>
    <fieldset
        id="person-kidnap"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('person_legend_kidnap') ?></legend>
        <p class="info"><?php echo I18n::__('person_info_kidnap') ?></p>
        <div
            id="person-<?php echo $record->getId() ?>-kidnap-container"
            class="container attachable detachable sortable">
            <?php if (count($record->ownKidnap) == 0) $record->ownKidnap[] = R::dispense('kidnap') ?>
            <?php $index = 0 ?>
            <?php foreach ($record->ownKidnap as $_kidnap_id => $_kidnap): ?>
            <?php $index++ ?>
            <?php Flight::render('model/person/own/kidnap', array(
                'record' => $record,
                '_kidnap' => $_kidnap,
                'index' => $index
            )) ?>
            <?php endforeach ?>
        </div>
    </fieldset>
</div>
<!-- end of person edit form -->