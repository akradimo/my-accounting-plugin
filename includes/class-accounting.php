<?php
if (!defined('ABSPATH')) {
    exit;
}

class Accounting {
    public function save_product($product_data) {
        if (!empty($product_data['id'])) {
            error_log("Saving product with ID: " . $product_data['id']); // لاگ برای بررسی ذخیره محصول
            update_option('my_accounting_product_' . $product_data['id'], $product_data);
            error_log("Product saved: " . print_r($product_data, true)); // لاگ برای بررسی داده‌های ذخیره‌شده
        } else {
            error_log("Product data is empty or invalid."); // لاگ برای بررسی داده‌های نامعتبر
        }
    }

    public function get_products() {
        global $wpdb;
        $products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE 'my_accounting_product_%'");
        error_log("Retrieved products: " . print_r($products, true)); // لاگ برای بررسی محصولات بازیابی‌شده
        return $products;
    }
}