<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

// دریافت اطلاعات محصول
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}products WHERE id = $product_id", ARRAY_A);

    if (!$product) {
        echo "<p>محصول یافت نشد.</p>";
        return;
    }
} else {
    echo "<p>شناسه محصول نامعتبر است.</p>";
    return;
}

// پردازش فرم ویرایش محصول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-edit-product'])) {
    $data = array(
        'name' => sanitize_text_field($_POST['product_name']),
        'group_id' => intval($_POST['product_group']),
        'subgroup_id' => intval($_POST['product_subgroup']),
        'barcode' => sanitize_text_field($_POST['barcode']),
        'unit_id' => intval($_POST['unit']),
        'initial_stock' => intval($_POST['initial_stock']),
        'purchase_price' => floatval($_POST['purchase_price']),
        'sale_price' => floatval($_POST['sale_price']),
        'tax_percentage' => floatval($_POST['tax_percentage']),
        'description' => sanitize_textarea_field($_POST['description'])
    );

    $wpdb->update(
        "{$wpdb->prefix}products",
        $data,
        array('id' => $product_id)
    );

    echo "<p>محصول با موفقیت ویرایش شد.</p>";
}
?>

<div class="edit-product">
    <h1>ویرایش محصول</h1>
    <form method="post" action="">
        <!-- گروه محصول -->
        <label for="product_group">گروه محصول:</label>
        <select id="product_group" name="product_group">
            <?php
            $groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_groups", ARRAY_A);
            foreach ($groups as $group) {
                $selected = ($group['id'] == $product['group_id']) ? 'selected' : '';
                echo "<option value='{$group['id']}' $selected>{$group['name']}</option>";
            }
            ?>
        </select>
        <br>

        <!-- زیرگروه محصول -->
        <label for="product_subgroup">زیرگروه محصول:</label>
        <select id="product_subgroup" name="product_subgroup">
            <?php
            $subgroups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_subgroups", ARRAY_A);
            foreach ($subgroups as $subgroup) {
                $selected = ($subgroup['id'] == $product['subgroup_id']) ? 'selected' : '';
                echo "<option value='{$subgroup['id']}' $selected>{$subgroup['name']}</option>";
            }
            ?>
        </select>
        <br>

        <!-- نام محصول -->
        <label for="product_name">نام محصول:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo esc_attr($product['name']); ?>" required>
        <br>

        <!-- بارکد -->
        <label for="barcode">بارکد:</label>
        <input type="text" id="barcode" name="barcode" value="<?php echo esc_attr($product['barcode']); ?>">
        <br>

        <!-- بارکد دوم -->
        <label for="barcode2">بارکد دوم:</label>
        <input type="text" id="barcode2" name="barcode2" value="<?php echo esc_attr($product['barcode2']); ?>">
        <br>

        <!-- واحد -->
        <label for="unit">واحد:</label>
        <select id="unit" name="unit" required>
            <?php
            $units = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_units", ARRAY_A);
            foreach ($units as $unit) {
                $selected = ($unit['id'] == $product['unit_id']) ? 'selected' : '';
                echo "<option value='{$unit['id']}' $selected>{$unit['name']}</option>";
            }
            ?>
        </select>
        <br>

        <!-- موجودی اولیه -->
        <label for="initial_stock">موجودی اولیه:</label>
        <input type="number" id="initial_stock" name="initial_stock" value="<?php echo esc_attr($product['initial_stock']); ?>" required>
        <br>

        <!-- قیمت خرید -->
        <label for="purchase_price">قیمت خرید:</label>
        <input type="number" id="purchase_price" name="purchase_price" value="<?php echo esc_attr($product['purchase_price']); ?>" step="0.01" required>
        <br>

        <!-- قیمت فروش -->
        <label for="sale_price">قیمت فروش:</label>
        <input type="number" id="sale_price" name="sale_price" value="<?php echo esc_attr($product['sale_price']); ?>" step="0.01" required>
        <br>

        <!-- درصد مالیات -->
        <label for="tax_percentage">درصد مالیات:</label>
        <input type="number" id="tax_percentage" name="tax_percentage" value="<?php echo esc_attr($product['tax_percentage']); ?>" step="0.01">
        <br>

        <!-- توضیحات -->
        <label for="description">توضیحات:</label>
        <textarea id="description" name="description"><?php echo esc_textarea($product['description']); ?></textarea>
        <br>

        <!-- دکمه ثبت -->
        <button type="submit" name="submit-edit-product">ذخیره تغییرات</button>
    </form>
</div>