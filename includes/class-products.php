<?php
if (!defined('ABSPATH')) {
    exit;
}

class Products {
    public function __construct() {
        // افزودن اکشن‌ها و فیلترها
    }

    public function add_product($data) {
        // افزودن محصول به دیتابیس
    }

    public function edit_product($id, $data) {
        // ویرایش محصول در دیتابیس
    }

    public function delete_product($id) {
        // حذف محصول از دیتابیس
    }

    public function copy_product($id) {
        // کپی محصول در دیتابیس
    }

    public function change_price($id, $new_price) {
        // تغییر قیمت محصول
    }

    public function get_products() {
        // دریافت لیست محصولات از دیتابیس
    }
    
}
// دریافت گروه‌های محصول از دیتابیس
function get_product_groups() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_groups", ARRAY_A);
}

// دریافت زیرگروه‌های محصول از دیتابیس
function get_product_subgroups() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_subgroups", ARRAY_A);
}

// دریافت واحدها از دیتابیس
function get_product_units() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_units", ARRAY_A);
}
// ثبت گروه جدید
function add_product_group($name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_groups';
    $wpdb->insert(
        $table_name,
        array('name' => $name)
    );
}

function add_product_subgroup($name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_subgroups';
    $wpdb->insert(
        $table_name,
        array('name' => $name)
    );
}

function add_product_unit($name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_units';
    $wpdb->insert(
        $table_name,
        array('name' => $name)
    );
}
new Products();