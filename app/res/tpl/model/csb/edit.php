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
<!-- csb edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
    <input type="hidden" name="dialog[extension]" value="<?php echo htmlspecialchars($record->extension) ?>" />
    <input type="hidden" name="dialog[size]" value="<?php echo htmlspecialchars($record->size) ?>" />
    <input type="hidden" name="dialog[mime]" value="<?php echo htmlspecialchars($record->mime) ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('csb_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('file')) ? 'error' : ''; ?>">
        <label
            for="csb-file">
            <?php echo I18n::__('csb_label_file') ?>
        </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Flight::get('max_upload_size') ?>" />
        <input
            id="csb-file"
            type="file"
            name="file"
            value="<?php echo htmlspecialchars($record->file) ?>" />
    </div>
    <div class="row <?php echo ($record->hasError('pubdate')) ? 'error' : ''; ?>">
        <label
            for="csb-pubdate">
            <?php echo I18n::__('csb_label_pubdate') ?>
        </label>
        <input
            id="csb-pubdate"
            type="date"
            name="dialog[pubdate]"
            value="<?php echo htmlspecialchars($record->pubdate) ?>"
            required="required" />
        <p class="info"><?php echo I18n::__('csb_info_pubdate') ?></p>
    </div>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="csb-company">
            <?php echo I18n::__('csb_label_company') ?>
        </label>
        <select
            id="csb-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company): ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('csbformat_id')) ? 'error' : ''; ?>">
        <label
            for="csb-csbformat">
            <?php echo I18n::__('csb_label_csbformat') ?>
        </label>
        <select
            id="csb-csbformat"
            name="dialog[csbformat_id]">
            <?php foreach (R::find('csbformat', ' active = 1 ORDER BY name') as $_id => $_csbformat): ?>
            <option
                value="<?php echo $_csbformat->getId() ?>"
                <?php echo ($record->csbformat_id == $_csbformat->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_csbformat->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('baseprice')) ? 'error' : ''; ?>">
        <label
            for="stock-baseprice">
            <?php echo I18n::__('csb_label_baseprice') ?>
        </label>
        <input
            id="stock-baseprice"
            type="text"
            class="number"
            name="dialog[baseprice]"
            value="<?php echo htmlspecialchars($record->decimal('baseprice', 3)) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of csb edit form -->