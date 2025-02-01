<?php
/*
Plugin Name: پلاگین حسابداری من
Description: یک پلاگین برای مدیریت محصولات، انبار و فاکتورها.
Version: 1.0
Author: نام شما
*/

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit;
}

// افزودن فایل‌های مورد نیاز
require_once plugin_dir_path(__FILE__) . 'includes/class-products.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-groups.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-accounting.php';

// تابع ایجاد جدول‌ها
function create_product_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // ایجاد جدول محصولات
    $table_name_products = $wpdb->prefix . 'products';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_products'") != $table_name_products) {
        $sql_products = "CREATE TABLE $table_name_products (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            group_id mediumint(9),
            subgroup_id mediumint(9),
            barcode varchar(255),
            barcode2 varchar(255),
            unit_id mediumint(9) NOT NULL,
            initial_stock int(11) NOT NULL,
            purchase_price decimal(10,2) NOT NULL,
            sale_price decimal(10,2) NOT NULL,
            tax_percentage decimal(5,2),
            description text,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_products);
    }

    // ایجاد جدول گروه‌ها
    $table_name_groups = $wpdb->prefix . 'product_groups';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_groups'") != $table_name_groups) {
        $sql_groups = "CREATE TABLE $table_name_groups (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_groups);
    }

    // ایجاد جدول زیرگروه‌ها
    $table_name_subgroups = $wpdb->prefix . 'product_subgroups';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_subgroups'") != $table_name_subgroups) {
        $sql_subgroups = "CREATE TABLE $table_name_subgroups (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_subgroups);
    }

    // ایجاد جدول واحدها
    $table_name_units = $wpdb->prefix . 'product_units';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_units'") != $table_name_units) {
        $sql_units = "CREATE TABLE $table_name_units (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_units);
    }
}
register_activation_hook(__FILE__, 'create_product_tables');

// افزودن منو به پنل مدیریت وردپرس
function my_accounting_plugin_menu() {
    add_menu_page(
        'پلاگین حسابداری',
        'حسابداری',
        'manage_options',
        'my-accounting-plugin',
        'my_accounting_plugin_dashboard',
        'dashicons-cart',
        6
    );

    // زیرمنوها
    add_submenu_page(
        'my-accounting-plugin',
        'محصولات',
        'محصولات',
        'manage_options',
        'my-accounting-plugin-products',
        'my_accounting_plugin_products'
    );

    add_submenu_page(
        'my-accounting-plugin',
        'افزودن محصول',
        'افزودن محصول',
        'manage_options',
        'my-accounting-plugin-add-product',
        'my_accounting_plugin_add_product'
    );

    // منوی مدیریت گروه‌ها
    add_submenu_page(
        'my-accounting-plugin',
        'مدیریت گروه‌ها',
        'مدیریت گروه‌ها',
        'manage_options',
        'my-accounting-plugin-manage-groups',
        'my_accounting_plugin_manage_groups'
    );
}
add_action('admin_menu', 'my_accounting_plugin_menu');

// نمایش صفحه داشبورد
function my_accounting_plugin_dashboard() {
    include plugin_dir_path(__FILE__) . 'templates/dashboard.php';
}

// نمایش صفحه مدیریت محصولات
function my_accounting_plugin_products() {
    include plugin_dir_path(__FILE__) . 'templates/products.php';
}

// نمایش صفحه افزودن محصول
function my_accounting_plugin_add_product() {
    include plugin_dir_path(__FILE__) . 'templates/add-product.php';
}

// نمایش صفحه مدیریت گروه‌ها
function my_accounting_plugin_manage_groups() {
    include plugin_dir_path(__FILE__) . 'templates/manage-groups.php';
}

// افزودن استایل‌ها و اسکریپت‌ها
function my_accounting_plugin_enqueue_scripts() {
    wp_enqueue_style('my-accounting-plugin-style', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('my-accounting-plugin-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'my_accounting_plugin_enqueue_scripts');

// افزودن تابع پردازش درخواست AJAX
add_action('wp_ajax_get_product', 'handle_get_product');
add_action('wp_ajax_nopriv_get_product', 'handle_get_product'); // اگر نیاز به دسترسی برای کاربران غیر لاگین دارید

function handle_get_product() {
    if (isset($_GET['id'])) {
        $product_id = intval($_GET['id']);
        global $wpdb;
        $product = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}products WHERE id = $product_id", ARRAY_A);
        if ($product) {
            echo json_encode($product);
            wp_die();
        }
    }
    wp_die();
}
