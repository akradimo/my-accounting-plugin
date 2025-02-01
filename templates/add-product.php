<div class="add-product">
    <h1>افزودن محصول</h1>
    <form method="post" action="">
        <!-- گروه محصول -->
        <label for="product_group">گروه محصول:</label>
        <select id="product_group" name="product_group">
            <option value="">-- انتخاب کنید --</option>
            <?php
            global $wpdb;
            $groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_groups", ARRAY_A);
            foreach ($groups as $group) {
                echo "<option value='{$group['id']}'>{$group['name']}</option>";
            }
            ?>
        </select>
        <button type="button" onclick="openGroupModal()">+</button>
        <br>

        <!-- زیرگروه محصول -->
        <label for="product_subgroup">زیرگروه محصول:</label>
        <select id="product_subgroup" name="product_subgroup">
            <option value="">-- انتخاب کنید --</option>
            <?php
            $subgroups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_subgroups", ARRAY_A);
            foreach ($subgroups as $subgroup) {
                echo "<option value='{$subgroup['id']}'>{$subgroup['name']}</option>";
            }
            ?>
        </select>
        <button type="button" onclick="openSubgroupModal()">+</button>
        <br>

        <!-- نام محصول -->
        <label for="product_name">نام محصول:</label>
        <input type="text" id="product_name" name="product_name" required>
        <br>

        <!-- بارکد -->
        <label for="barcode">بارکد:</label>
        <input type="text" id="barcode" name="barcode">
        <br>

        <!-- بارکد دوم -->
        <label for="barcode2">بارکد دوم:</label>
        <input type="text" id="barcode2" name="barcode2">
        <br>

        <!-- واحد -->
        <label for="unit">واحد:</label>
        <select id="unit" name="unit" required>
            <option value="">-- انتخاب کنید --</option>
            <?php
            $units = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_units", ARRAY_A);
            foreach ($units as $unit) {
                echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
            }
            ?>
        </select>
        <button type="button" onclick="openUnitModal()">+</button>
        <br>

        <!-- موجودی اولیه -->
        <label for="initial_stock">موجودی اولیه:</label>
        <input type="number" id="initial_stock" name="initial_stock" required>
        <br>

        <!-- قیمت خرید -->
        <label for="purchase_price">قیمت خرید:</label>
        <input type="number" id="purchase_price" name="purchase_price" step="0.01" required>
        <br>

        <!-- قیمت فروش -->
        <label for="sale_price">قیمت فروش:</label>
        <input type="number" id="sale_price" name="sale_price" step="0.01" required>
        <br>

        <!-- درصد مالیات -->
        <label for="tax_percentage">درصد مالیات:</label>
        <input type="number" id="tax_percentage" name="tax_percentage" step="0.01">
        <br>

        <!-- توضیحات -->
        <label for="description">توضیحات:</label>
        <textarea id="description" name="description"></textarea>
        <br>

        <!-- دکمه ثبت -->
        <button type="submit" name="submit_product">ثبت محصول</button>
    </form>
</div>

<?php
// پردازش فرم افزودن محصول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_product'])) {
    global $wpdb;

    $data = array(
        'name' => sanitize_text_field($_POST['product_name']),
        'group_id' => !empty($_POST['product_group']) ? intval($_POST['product_group']) : NULL,
        'subgroup_id' => !empty($_POST['product_subgroup']) ? intval($_POST['product_subgroup']) : NULL,
        'barcode' => sanitize_text_field($_POST['barcode']),
        'barcode2' => sanitize_text_field($_POST['barcode2']),
        'unit_id' => intval($_POST['unit']),
        'initial_stock' => intval($_POST['initial_stock']),
        'purchase_price' => floatval($_POST['purchase_price']),
        'sale_price' => floatval($_POST['sale_price']),
        'tax_percentage' => floatval($_POST['tax_percentage']),
        'description' => sanitize_textarea_field($_POST['description'])
    );

    $result = $wpdb->insert("{$wpdb->prefix}products", $data);

    if ($result) {
        echo "<p>محصول با موفقیت اضافه شد.</p>";
    } else {
        echo "<p>خطا در اضافه کردن محصول: " . $wpdb->last_error . "</p>";
    }
}
?>