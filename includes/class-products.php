<?php
if (!defined('ABSPATH')) {
    exit;
}

class Products {
    public function __construct() {
        // افزودن اکشن‌ها و فیلترها
    }

    public function add_product($data) {
        global $wpdb;
        return $wpdb->insert("{$wpdb->prefix}products", $data);
    }

    public function edit_product($id, $data) {
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}products",
            $data,
            array('id' => $id)
        );
    }

    public function delete_product($id) {
        global $wpdb;
        return $wpdb->delete("{$wpdb->prefix}products", array('id' => $id));
    }

    public function copy_product($id) {
        global $wpdb;
        $product = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}products WHERE id = $id", ARRAY_A);
        if ($product) {
            unset($product['id']);
            return $wpdb->insert("{$wpdb->prefix}products", $product);
        }
        return false;
    }

    public function change_price($id, $new_price) {
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}products",
            array('sale_price' => $new_price),
            array('id' => $id)
        );
    }

    public function get_products() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}products", ARRAY_A);
    }

    public function get_product($id) {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}products WHERE id = $id", ARRAY_A);
    }
}

<<<<<<< Updated upstream
<<<<<<< Updated upstream
new Products();
=======
new Products();
>>>>>>> Stashed changes
=======
new Products();
>>>>>>> Stashed changes
