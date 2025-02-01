<div class="products">
    <h1>مدیریت محصولات</h1>
    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام محصول</th>
                <th>گروه</th>
                <th>زیرگروه</th>
                <th>بارکد</th>
                <th>واحد</th>
                <th>موجودی</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
                <th>مالیات</th>
                <th>توضیحات</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}products", ARRAY_A);

            if (!empty($products)) {
                foreach ($products as $product) {
                    echo "<tr>";
                    echo "<td>{$product['id']}</td>";
                    echo "<td>{$product['name']}</td>";
                    echo "<td>{$product['group_id']}</td>";
                    echo "<td>{$product['subgroup_id']}</td>";
                    echo "<td>{$product['barcode']}</td>";
                    echo "<td>{$product['unit_id']}</td>";
                    echo "<td>{$product['initial_stock']}</td>";
                    echo "<td>{$product['purchase_price']}</td>";
                    echo "<td>{$product['sale_price']}</td>";
                    echo "<td>{$product['tax_percentage']}</td>";
                    echo "<td>{$product['description']}</td>";
                    echo "<td>
                            <a href='?page=my-accounting-plugin-edit-product&id={$product['id']}'>ویرایش</a> |
                            <a href='?page=my-accounting-plugin-products&action=delete&id={$product['id']}' onclick='return confirm(\"آیا مطمئن هستید؟\");'>حذف</a> |
                            <a href='?page=my-accounting-plugin-products&action=copy&id={$product['id']}'>کپی</a> |
                            <a href='?page=my-accounting-plugin-products&action=change_price&id={$product['id']}'>تغییر قیمت</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>هیچ محصولی یافت نشد.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>