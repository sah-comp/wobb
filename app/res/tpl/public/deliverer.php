<article class="main">
    <section>
        <table>
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
                    <td>
                        <?php echo $_record['marginmax'] ?>
                    </td>
                    <td>
                        <?php echo $_record['pnextweekprice'] ?>
                    </td>
                    <td>
                        <?php echo $_record['reldprice'] ?>
                    </td>
                    <td>
                        <?php echo $_record['costvalue'] ?>
                    </td>
                    <td>
                        <?php echo $_record['conditionvalue'] ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
</article>
