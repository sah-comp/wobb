<article class="main">
    <section class="decay">
        <table class="decay">
            <caption>
                <h1><?php echo I18n::__('app_name') ?></h1>
                <p><?php echo count($records) . ' Lieferanten' ?></p>
            </caption>
            <thead class="fixable">
                <tr>
                    <th class="nickname">Zeichen</th>
                    <th class="account">Konto</th>
                    <th class="name">Lieferant</th>
                    <th class="pricemask">Preismaske</th>
                    <th class="maxagio">Maximaler Aufschlag</th>
                    <th class="price">Mittwochspreis</th>
                    <th class="reldprice">Aufschlag</th>
                    <th class="cost">Kosten</th>
                    <th class="bonus">Bonus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $_id => $_record) : ?>
                <tr>
                    <td>
                        <?php echo $_record['nickname'] ?>
                    </td>
                    <td>
                        <?php echo $_record['account'] ?>
                    </td>
                    <td>
                        <?php echo $_record['name'] ?>
                    </td>
                    <td>
                        <?php echo $_record['pricingname'] ?>
                    </td>
                    <td class="numval">
                        <?php echo $_record['marginmax'] ?>
                    </td>
                    <td>
                        <?php echo $_record['pnextweekprice'] ?>
                    </td>
                    <td class="numval">
                        <?php echo $_record['reldprice'] ?>
                    </td>
                    <td class="numval">
                        <?php echo $_record['costvalue'] ?>
                    </td>
                    <td class="numval">
                        <?php echo $_record['conditionvalue'] ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
</article>
