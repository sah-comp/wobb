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
<!-- company edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('company_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('name')) ? 'error' : ''; ?>">
        <label
            for="company-name">
            <?php echo I18n::__('company_label_name') ?>
        </label>
        <input
            id="company-name"
            type="text"
            name="dialog[name]"
            value="<?php echo htmlspecialchars($record->name) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('active')) ? 'error' : ''; ?>">
        <input
            type="hidden"
            name="dialog[active]"
            value="0" />
        <input
            id="company-active"
            type="checkbox"
            name="dialog[active]"
            <?php echo ($record->active) ? 'checked="checked"' : '' ?>
            value="1" />
        <label
            for="company-active"
            class="cb">
            <?php echo I18n::__('company_label_active') ?>
        </label>
    </div>
</fieldset>
<div class="tab-container">
    <?php Flight::render('shared/navigation/tabs', array(
        'tab_id' => 'company-tabs',
        'tabs' => array(
            'company-address' => I18n::__('company_address_tab'),
            'company-communication' => I18n::__('company_communication_tab'),
            'company-id' => I18n::__('company_id_tab'),
            'company-bankaccount' => I18n::__('company_bankaccount_tab'),
            'company-serial' => I18n::__('company_serial_tab'),
            'company-lanuv' => I18n::__('company_lanuv_tab'),
            'company-iqagrar' => I18n::__('company_iqagrar_tab'),
            'company-tierwohl' => I18n::__('company_tierwohl_tab')
        ),
        'default_tab' => 'company-address'
    )) ?>
    <fieldset
        id="company-address"
        class="tab"
        style="display: block;">
        <legend class="verbose"><?php echo I18n::__('company_legend_address_tab') ?></legend>
            <div class="row <?php echo ($record->hasError('legalname')) ? 'error' : ''; ?>">
                <label
                    for="company-legalname">
                    <?php echo I18n::__('company_label_legalname') ?>
                </label>
                <input
                    id="company-legalname"
                    type="text"
                    name="dialog[legalname]"
                    value="<?php echo htmlspecialchars($record->legalname) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('street')) ? 'error' : ''; ?>">
                <label
                    for="company-street">
                    <?php echo I18n::__('company_label_street') ?>
                </label>
                <input
                    id="company-street"
                    type="text"
                    name="dialog[street]"
                    value="<?php echo htmlspecialchars($record->street) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('zip')) ? 'error' : ''; ?>">
                <label
                    for="company-zip">
                    <?php echo I18n::__('company_label_zip') ?>
                </label>
                <input
                    id="company-zip"
                    type="text"
                    name="dialog[zip]"
                    value="<?php echo htmlspecialchars($record->zip) ?>" />
            </div>
            <div class="row <?php echo ($record->hasError('city')) ? 'error' : ''; ?>">
                <label
                    for="company-city">
                    <?php echo I18n::__('company_label_city') ?>
                </label>
                <input
                    id="company-city"
                    type="text"
                    name="dialog[city]"
                    value="<?php echo htmlspecialchars($record->city) ?>" />
            </div>
    </fieldset>
    <fieldset
        id="company-communication"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_communication') ?></legend>
        <div class="row <?php echo ($record->hasError('phone')) ? 'error' : ''; ?>">
            <label
                for="company-phone">
                <?php echo I18n::__('company_label_phone') ?>
            </label>
            <input
                id="company-phone"
                type="text"
                name="dialog[phone]"
                value="<?php echo htmlspecialchars($record->phone) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('fax')) ? 'error' : ''; ?>">
            <label
                for="company-fax">
                <?php echo I18n::__('company_label_fax') ?>
            </label>
            <input
                id="company-fax"
                type="text"
                name="dialog[fax]"
                value="<?php echo htmlspecialchars($record->fax) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('email')) ? 'error' : ''; ?>">
            <label
                for="company-email">
                <?php echo I18n::__('company_label_email') ?>
            </label>
            <input
                id="company-email"
                type="email"
                name="dialog[email]"
                value="<?php echo htmlspecialchars($record->email) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('website')) ? 'error' : ''; ?>">
            <label
                for="company-website">
                <?php echo I18n::__('company_label_website') ?>
            </label>
            <input
                id="company-website"
                type="text"
                name="dialog[website]"
                value="<?php echo htmlspecialchars($record->website) ?>" />
        </div>
        <!-- Noreply email address and its smtp Server -->
        <hr />
        <div class="row <?php echo ($record->hasError('emailnoreply')) ? 'error' : ''; ?>">
            <label
                for="company-emailnoreply">
                <?php echo I18n::__('company_label_emailnoreply') ?>
            </label>
            <input
                id="company-emailnoreply"
                type="email"
                name="dialog[emailnoreply]"
                value="<?php echo htmlspecialchars($record->emailnoreply) ?>" />
            <p class="info"><?php echo I18n::__('company_info_emailnoreply') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('smtphost')) ? 'error' : ''; ?>">
            <label
                for="company-smtphost">
                <?php echo I18n::__('company_label_smtphost') ?>
            </label>
            <input
                id="company-smtphost"
                type="text"
                name="dialog[smtphost]"
                value="<?php echo htmlspecialchars($record->smtphost) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpport')) ? 'error' : ''; ?>">
            <label
                for="company-smtpport">
                <?php echo I18n::__('company_label_smtpport') ?>
            </label>
            <input
                id="company-smtpport"
                type="text"
                name="dialog[smtpport]"
                value="<?php echo htmlspecialchars($record->smtpport) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpauth')) ? 'error' : ''; ?>">
            <label
                for="company-smtpauth">
                <?php echo I18n::__('company_label_smtpauth') ?>
            </label>
            <input
                id="company-smtpauth"
                type="text"
                name="dialog[smtpauth]"
                value="<?php echo htmlspecialchars($record->smtpauth) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtpuser')) ? 'error' : ''; ?>">
            <label
                for="company-smtpuser">
                <?php echo I18n::__('company_label_smtpuser') ?>
            </label>
            <input
                id="company-smtpuser"
                type="text"
                name="dialog[smtpuser]"
                value="<?php echo htmlspecialchars($record->smtpuser) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('smtppwd')) ? 'error' : ''; ?>">
            <label
                for="company-smtppwd">
                <?php echo I18n::__('company_label_smtppwd') ?>
            </label>
            <input
                id="company-smtppwd"
                type="password"
                name="dialog[smtppwd]"
                value="<?php echo htmlspecialchars($record->smtppwd) ?>" />
        </div>
        <!-- /Noreply email address and its smtp Server -->
    </fieldset>
    <fieldset
        id="company-id"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_id') ?></legend>
        <div class="row <?php echo ($record->hasError('taxoffice')) ? 'error' : ''; ?>">
            <label
                for="company-taxoffice">
                <?php echo I18n::__('company_label_taxoffice') ?>
            </label>
            <input
                id="company-taxoffice"
                type="text"
                name="dialog[taxoffice]"
                value="<?php echo htmlspecialchars($record->taxoffice) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('taxid')) ? 'error' : ''; ?>">
            <label
                for="company-taxid">
                <?php echo I18n::__('company_label_taxid') ?>
            </label>
            <input
                id="company-taxid"
                type="text"
                name="dialog[taxid]"
                value="<?php echo htmlspecialchars($record->taxid) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('vatid')) ? 'error' : ''; ?>">
            <label
                for="company-vatid">
                <?php echo I18n::__('company_label_vatid') ?>
            </label>
            <input
                id="company-vatid"
                type="text"
                name="dialog[vatid]"
                value="<?php echo htmlspecialchars($record->vatid) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('taxconsultantemail')) ? 'error' : ''; ?>">
            <label
                for="company-taxconsultantemail">
                <?php echo I18n::__('company_label_taxconsultantemail') ?>
            </label>
            <input
                id="company-taxconsultantemail"
                type="email"
                name="dialog[taxconsultantemail]"
                value="<?php echo htmlspecialchars($record->taxconsultantemail) ?>" />
            <p class="info"><?php echo I18n::__('company_info_taxconsultantemail') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="company-bankaccount"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_bankaccount_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('bankname')) ? 'error' : ''; ?>">
            <label
                for="company-bankname">
                <?php echo I18n::__('company_label_bankname') ?>
            </label>
            <input
                id="company-bankname"
                type="text"
                name="dialog[bankname]"
                value="<?php echo htmlspecialchars($record->bankname) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankcode')) ? 'error' : ''; ?>">
            <label
                for="company-bankcode">
                <?php echo I18n::__('company_label_bankcode') ?>
            </label>
            <input
                id="company-bankcode"
                type="text"
                name="dialog[bankcode]"
                value="<?php echo htmlspecialchars($record->bankcode) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bankaccount')) ? 'error' : ''; ?>">
            <label
                for="company-bankaccountfield">
                <?php echo I18n::__('company_label_bankaccount') ?>
            </label>
            <input
                id="company-bankaccountfield"
                type="text"
                name="dialog[bankaccount]"
                value="<?php echo htmlspecialchars($record->bankaccount) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('bic')) ? 'error' : ''; ?>">
            <label
                for="company-bic">
                <?php echo I18n::__('company_label_bic') ?>
            </label>
            <input
                id="company-bic"
                type="text"
                name="dialog[bic]"
                value="<?php echo htmlspecialchars($record->bic) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('iban')) ? 'error' : ''; ?>">
            <label
                for="company-iban">
                <?php echo I18n::__('company_label_iban') ?>
            </label>
            <input
                id="company-iban"
                type="text"
                name="dialog[iban]"
                value="<?php echo htmlspecialchars($record->iban) ?>" />
        </div>
    </fieldset>
    <fieldset
        id="company-serial"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_serial_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('buyer')) ? 'error' : ''; ?>">
            <label
                for="company-buyer">
                <?php echo I18n::__('company_label_buyer') ?>
            </label>
            <input
                id="company-buyer"
                type="text"
                name="dialog[buyer]"
                value="<?php echo htmlspecialchars($record->buyer) ?>"
                required="required" />
        </div>
        <div class="row <?php echo ($record->hasError('nextbillingnumber')) ? 'error' : ''; ?>">
            <label
                for="company-nextbillingnumber">
                <?php echo I18n::__('company_label_nextbillingnumber') ?>
            </label>
            <input
                id="company-nextbillingnumber"
                type="number"
                step="1"
                name="dialog[nextbillingnumber]"
                value="<?php echo htmlspecialchars($record->nextbillingnumber) ?>"
                required="required" />
        </div>
    </fieldset>
    <fieldset
        id="company-lanuv"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_lanuv_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('vvvo')) ? 'error' : ''; ?>">
            <label
                for="company-vvvo">
                <?php echo I18n::__('company_label_vvvo') ?>
            </label>
            <input
                id="company-vvvo"
                type="text"
                name="dialog[vvvo]"
                value="<?php echo htmlspecialchars($record->vvvo) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('county')) ? 'error' : ''; ?>">
            <label
                for="company-county">
                <?php echo I18n::__('company_label_county') ?>
            </label>
            <input
                id="company-county"
                type="text"
                name="dialog[county]"
                value="<?php echo htmlspecialchars($record->county) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('region')) ? 'error' : ''; ?>">
            <label
                for="company-region">
                <?php echo I18n::__('company_label_region') ?>
            </label>
            <input
                id="company-region"
                type="text"
                name="dialog[region]"
                value="<?php echo htmlspecialchars($record->region) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('subpart')) ? 'error' : ''; ?>">
            <label
                for="company-subpart">
                <?php echo I18n::__('company_label_subpart') ?>
            </label>
            <input
                id="company-subpart"
                type="text"
                name="dialog[subpart]"
                value="<?php echo htmlspecialchars($record->subpart) ?>" />
        </div>
        <div class="row <?php echo ($record->hasError('lanuvemail')) ? 'error' : ''; ?>">
            <label
                for="company-lanuvemail">
                <?php echo I18n::__('company_label_lanuvemail') ?>
            </label>
            <input
                id="company-lanuvemail"
                type="text"
                name="dialog[lanuvemail]"
                value="<?php echo htmlspecialchars($record->lanuvemail) ?>" />
            <p class="info"><?php echo I18n::__('company_info_lanuvemail') ?></p>
        </div>
    </fieldset>
    <fieldset
        id="company-iqagrar"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_iqagrar_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('hasiqagrar')) ? 'error' : ''; ?>">
            <label
                for="company-hasiqagrar"
				class="cb">
                <?php echo I18n::__('company_label_hasiqagrar') ?>
            </label>
	        <input
	            type="hidden"
	            name="dialog[hasiqagrar]"
	            value="0" />
	        <input
	            id="company-hasiqagrar"
	            type="checkbox"
	            name="dialog[hasiqagrar]"
	            <?php echo ($record->hasiqagrar) ? 'checked="checked"' : '' ?>
	            value="1" />
        </div>
        <div class="row <?php echo ($record->hasError('ident')) ? 'error' : ''; ?>">
            <label
                for="company-ident">
                <?php echo I18n::__('company_label_ident') ?>
            </label>
            <input
                id="company-ident"
                type="text"
                name="dialog[ident]"
                value="<?php echo htmlspecialchars($record->ident) ?>" />
            <p class="info"><?php echo I18n::__('company_info_ident') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('iqagraremail')) ? 'error' : ''; ?>">
            <label
                for="company-iqagraremail">
                <?php echo I18n::__('company_label_iqagraremail') ?>
            </label>
            <input
                id="company-iqagraremail"
                type="text"
                name="dialog[iqagraremail]"
                value="<?php echo htmlspecialchars($record->iqagraremail) ?>" />
            <p class="info"><?php echo I18n::__('company_info_iqagraremail') ?></p>
        </div>
	</fieldset>
    <fieldset
        id="company-tierwohl"
        class="tab"
        style="display: none;">
        <legend class="verbose"><?php echo I18n::__('company_legend_tierwohl_tab') ?></legend>
        <div class="row <?php echo ($record->hasError('hastierwohl')) ? 'error' : ''; ?>">
            <label
                for="company-hastierwohl"
				class="cb">
                <?php echo I18n::__('company_label_hastierwohl') ?>
            </label>
	        <input
	            type="hidden"
	            name="dialog[hastierwohl]"
	            value="0" />
	        <input
	            id="company-hastierwohl"
	            type="checkbox"
	            name="dialog[hastierwohl]"
	            <?php echo ($record->hastierwohl) ? 'checked="checked"' : '' ?>
	            value="1" />
        </div>
        <div class="row <?php echo ($record->hasError('tierwohlflag')) ? 'error' : ''; ?>">
            <label
                for="company-tierwohlflag">
                <?php echo I18n::__('company_label_tierwohlflag') ?>
            </label>
            <input
                id="company-tierwohlflag"
                type="text"
                name="dialog[tierwohlflag]"
                value="<?php echo htmlspecialchars($record->tierwohlflag) ?>" />
            <p class="info"><?php echo I18n::__('company_info_tierwohlflag') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('tierwohlident')) ? 'error' : ''; ?>">
            <label
                for="company-tierwohlident">
                <?php echo I18n::__('company_label_tierwohlident') ?>
            </label>
            <input
                id="company-tierwohlident"
                type="text"
                name="dialog[tierwohlident]"
                value="<?php echo htmlspecialchars($record->tierwohlident) ?>" />
            <p class="info"><?php echo I18n::__('company_info_tierwohlident') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('tierwohlnetperstock')) ? 'error' : ''; ?>">
            <label
                for="company-tierwohlnetperstock">
                <?php echo I18n::__('company_label_tierwohlnetperstock') ?>
            </label>
            <input
                id="company-tierwohlnetperstock"
                type="text"
                name="dialog[tierwohlnetperstock]"
                value="<?php echo htmlspecialchars($record->decimal('tierwohlnetperstock', 3)) ?>" />
            <p class="info"><?php echo I18n::__('company_info_tierwohlnetperstock') ?></p>
        </div>
        <div class="row <?php echo ($record->hasError('vat_id')) ? 'error' : ''; ?>">
            <label
                for="company-tierwohl-vat">
                <?php echo I18n::__('company_label_companytierwohlvat') ?>
            </label>
 	        <select
 	            id="company-tierwohl-vat"
 	            name="dialog[vat_id]"
                required="required">
 	            <option value=""><?php echo I18n::__('company_tierwohl_vat_please_select') ?></option>
 	            <?php foreach (R::find('vat', ' ORDER BY name') as $_id => $_vat): ?>
 	            <option
 	                value="<?php echo $_vat->getId() ?>"
 	                <?php echo ($record->vat_id == $_vat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_vat->name) ?></option>
 	            <?php endforeach ?>
 	        </select>
 		</div>
        <div class="row <?php echo ($record->hasError('wsdl')) ? 'error' : ''; ?>">
            <label
                for="company-wsdl">
                <?php echo I18n::__('company_label_wsdl') ?>
            </label>
            <input
                id="company-wsdl"
                type="text"
                name="dialog[wsdl]"
                value="<?php echo htmlspecialchars($record->wsdl) ?>" />
            <p class="info"><?php echo I18n::__('company_info_wsdl') ?></p>
        </div>
	</fieldset>
</div>
<!-- end of company edit form -->
