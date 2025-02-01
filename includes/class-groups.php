<?php
if (!defined('ABSPATH')) {
    exit;
}

class Groups {
    public function __construct() {
        // افزودن اکشن‌ها و فیلترها
    }

    public function add_group($name) {
        global $wpdb;
        return $wpdb->insert(
            "{$wpdb->prefix}product_groups",
            array('name' => $name)
        );
    }

    public function edit_group($id, $name) {
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}product_groups",
            array('name' => $name),
            array('id' => $id)
        );
    }

    public function delete_group($id) {
        global $wpdb;
        return $wpdb->delete(
            "{$wpdb->prefix}product_groups",
            array('id' => $id)
        );
    }

    public function get_groups() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}product_groups", ARRAY_A);
    }
}