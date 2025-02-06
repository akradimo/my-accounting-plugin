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
                            <button class='edit-product' data-id='{$product['id']}'>ویرایش</button> |
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

<!-- پاپ‌آپ ویرایش محصول -->
<div id="edit-product-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>ویرایش محصول</h2>
        <form id="edit-product-form" method="post" action="">
            <input type="hidden" id="edit-product-id" name="edit-product-id">
            <label for="edit-product-name">نام محصول:</label>
            <input type="text" id="edit-product-name" name="edit-product-name" required>
            <br>
            <label for="edit-product-group">گروه محصول:</label>
            <select id="edit-product-group" name="edit-product-group">
                <?php
                $groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_groups", ARRAY_A);
                foreach ($groups as $group) {
                    echo "<option value='{$group['id']}'>{$group['name']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="edit-product-subgroup">زیرگروه محصول:</label>
            <select id="edit-product-subgroup" name="edit-product-subgroup">
                <?php
                $subgroups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_subgroups", ARRAY_A);
                foreach ($subgroups as $subgroup) {
                    echo "<option value='{$subgroup['id']}'>{$subgroup['name']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="edit-product-barcode">بارکد:</label>
            <input type="text" id="edit-product-barcode" name="edit-product-barcode">
            <br>
            <label for="edit-product-unit">واحد:</label>
            <select id="edit-product-unit" name="edit-product-unit">
                <?php
                $units = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_units", ARRAY_A);
                foreach ($units as $unit) {
                    echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
                }
                ?>
            </select>
            <br>
            <label for="edit-product-stock">موجودی اولیه:</label>
            <input type="number" id="edit-product-stock" name="edit-product-stock" required>
            <br>
            <label for="edit-product-purchase-price">قیمت خرید:</label>
            <input type="number" id="edit-product-purchase-price" name="edit-product-purchase-price" step="0.01" required>
            <br>
            <label for="edit-product-sale-price">قیمت فروش:</label>
            <input type="number" id="edit-product-sale-price" name="edit-product-sale-price" step="0.01" required>
            <br>
            <label for="edit-product-tax">درصد مالیات:</label>
            <input type="number" id="edit-product-tax" name="edit-product-tax" step="0.01">
            <br>
            <label for="edit-product-description">توضیحات:</label>
            <textarea id="edit-product-description" name="edit-product-description"></textarea>
            <br>
            <button type="submit" name="submit-edit-product">ذخیره تغییرات</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editButtons = document.querySelectorAll('.edit-product');
    var modal = document.getElementById('edit-product-modal');
    var closeButton = document.querySelector('.close');

    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-id');
            // ارسال درخواست AJAX به admin-ajax.php
            fetch(`<?php echo admin_url('admin-ajax.php'); ?>?action=get_product&id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-product-id').value = data.id;
                    document.getElementById('edit-product-name').value = data.name;
                    document.getElementById('edit-product-group').value = data.group_id;
                    document.getElementById('edit-product-subgroup').value = data.subgroup_id;
                    document.getElementById('edit-product-barcode').value = data.barcode;
                    document.getElementById('edit-product-unit').value = data.unit_id;
                    document.getElementById('edit-product-stock').value = data.initial_stock;
                    document.getElementById('edit-product-purchase-price').value = data.purchase_price;
                    document.getElementById('edit-product-sale-price').value = data.sale_price;
                    document.getElementById('edit-product-tax').value = data.tax_percentage;
                    document.getElementById('edit-product-description').value = data.description;
                    modal.style.display = 'block';
                })
                .catch(error => console.error('خطا در دریافت اطلاعات محصول:', error));
        });
    });

    // بستن پاپ‌آپ
    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>

<?php
// پردازش فرم ویرایش محصول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-edit-product'])) {
    global $wpdb;
    $product_id = intval($_POST['edit-product-id']);
    $data = array(
        'name' => sanitize_text_field($_POST['edit-product-name']),
        'group_id' => intval($_POST['edit-product-group']),
        'subgroup_id' => intval($_POST['edit-product-subgroup']),
        'barcode' => sanitize_text_field($_POST['edit-product-barcode']),
        'unit_id' => intval($_POST['edit-product-unit']),
        'initial_stock' => intval($_POST['edit-product-stock']),
        'purchase_price' => floatval($_POST['edit-product-purchase-price']),
        'sale_price' => floatval($_POST['edit-product-sale-price']),
        'tax_percentage' => floatval($_POST['edit-product-tax']),
        'description' => sanitize_textarea_field($_POST['edit-product-description'])
    );

    $wpdb->update(
        "{$wpdb->prefix}products",
        $data,
        array('id' => $product_id)
    );
    echo "<p>محصول با موفقیت ویرایش شد.</p>";
<<<<<<< Updated upstream
}
=======
}
>>>>>>> Stashed changes
