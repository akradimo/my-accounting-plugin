<?php
if (!defined('ABSPATH')) {
    exit;
}

class Products_Handler {
    public function __construct() {
        // افزودن اکشن‌ها
        add_action('woocommerce_new_product', array($this, 'sync_product'));
        add_action('woocommerce_update_product', array($this, 'sync_product'));
        error_log("Products_Handler initialized."); // لاگ برای بررسی مقداردهی اولیه
    }
 
    public function sync_product($product_id) {
        error_log("sync_product called for product ID: " . $product_id); // لاگ برای بررسی فراخوانی متد
        $product = wc_get_product($product_id);
        if (!$product) {
            error_log("Product not found for ID: " . $product_id); // لاگ برای بررسی وجود محصول
            return;
        }

        $product_data = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'stock_quantity' => $product->get_stock_quantity(),
            'sku' => $product->get_sku(),
        );

        error_log("Product data: " . print_r($product_data, true)); // لاگ برای بررسی داده‌های محصول

        // ذخیره اطلاعات محصول در سیستم حسابداری
        $accounting = new Accounting();
        $accounting->save_product($product_data);
    }
}

new Products_Handler();