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
<!-- lanuv edit form -->
<div>
    <input type="hidden" name="dialog[type]" value="<?php echo $record->getMeta('type') ?>" />
    <input type="hidden" name="dialog[id]" value="<?php echo $record->getId() ?>" />
</div>
<fieldset>
    <legend class="verbose"><?php echo I18n::__('lanuv_legend') ?></legend>
    <div class="row <?php echo ($record->hasError('company_id')) ? 'error' : ''; ?>">
        <label
            for="lanuv-company">
            <?php echo I18n::__('lanuv_label_company') ?>
        </label>
        <select
            id="lanuv-company"
            name="dialog[company_id]">
            <?php foreach (R::find('company', ' active = 1 ORDER BY name') as $_id => $_company): ?>
            <option
                value="<?php echo $_company->getId() ?>"
                <?php echo ($record->company_id == $_company->getId()) ? 'selected="selected"' : '' ?>><?php echo htmlspecialchars($_company->name) ?></option>   
            <?php endforeach ?>
        </select>
    </div>
    <div class="row <?php echo ($record->hasError('startdate')) ? 'error' : ''; ?>">
        <label
            for="lanuv-startdate">
            <?php echo I18n::__('lanuv_label_startdate') ?>
        </label>
        <input
            id="lanuv-startdate"
            type="date"
			placeholder="yyyy-mm-dd"
            name="dialog[startdate]"
            value="<?php echo htmlspecialchars($record->startdate) ?>"
            required="required" />
    </div>
    <div class="row <?php echo ($record->hasError('enddate')) ? 'error' : ''; ?>">
        <label
            for="lanuv-enddate">
            <?php echo I18n::__('lanuv_label_enddate') ?>
        </label>
        <input
            id="lanuv-enddate"
            type="date"
			placeholder="yyyy-mm-dd"
            name="dialog[enddate]"
            value="<?php echo htmlspecialchars($record->enddate) ?>"
            required="required" />
    </div>
</fieldset>
<!-- end of lanuv edit form -->