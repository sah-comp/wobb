<table width="60%">
    <tr>
        <td colspan="3" class="dinky centered"><?php echo I18n::__('invoice_internal_label_pricing') ?></td>
    </tr>
    <tr>
        <td width="33.3%" class="br" style="vertical-align: top;">
            <table width="100%">
                <thead>
                    <tr>
                        <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_weightmargin') ?></td>
                    </tr>
                    <tr>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                        <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                    </tr>
                    <tbody>
                <?php foreach ($record->person->pricing->withCondition(" kind='weight' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                        <tr>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                            <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                        </tr>
                <?php endforeach ?>
                    </tbody>
                </thead>
            </table>
        </td>
        <td width="33.3%" class="br" style="vertical-align: top;">
            <table width="100%">
                <thead>
                    <tr>
                        <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfamargin') ?></td>
                    </tr>
                    <tr>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                        <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                    </tr>
                    <tbody>
                <?php foreach ($record->person->pricing->withCondition(" kind='mfa' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                        <tr>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                            <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                        </tr>
                <?php endforeach ?>
                    </tbody>
                </thead>
            </table>
        </td>
        <td width="33.3%" class="br" style="vertical-align: top;">
            <table width="100%">
                <thead>
                    <tr>
                        <td colspan="4" class="moredinky centered"><?php echo I18n::__('invoice_internal_label_mfasubmargin') ?></td>
                    </tr>
                    <tr>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_lo') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_hi') ?></th>
                        <th class="moredinky"><?php echo I18n::__('invoice_internal_label_kind') ?></th>
                        <th class="moredinky number"><?php echo I18n::__('invoice_internal_label_value') ?></th>
                    </tr>
                    <tbody>
                <?php foreach ($record->person->pricing->withCondition(" kind='mfasub' ORDER BY lo ASC ")->ownMargin as $_id => $_margin): ?>
                        <tr>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('lo', 1)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('hi', 1)) ?></td>
                            <td class="moredinky"><?php echo htmlspecialchars(I18n::__('margin_label_' . $_margin->op)) ?></td>
                            <td class="moredinky number"><?php echo htmlspecialchars($_margin->decimal('value', 3)) ?></td>
                        </tr>
                <?php endforeach ?>
                    </tbody>
                </thead>
            </table>
        </td>
    </tr>
</table>
