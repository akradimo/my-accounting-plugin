<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$groups = new Groups();

// پردازش فرم افزودن گروه
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_new_group'])) {
    if (isset($_POST['add_group_nonce']) && wp_verify_nonce($_POST['add_group_nonce'], 'add_group_action')) {
        $new_group_name = sanitize_text_field($_POST['new_group_name']);
        $groups->add_group($new_group_name);
        echo "<p>گروه با موفقیت اضافه شد.</p>";
    }
}

// پردازش عملیات ویرایش و حذف
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = sanitize_text_field($_GET['action']);
    $id = intval($_GET['id']);

    if ($action === 'delete') {
        $groups->delete_group($id);
        echo "<p>گروه با موفقیت حذف شد.</p>";
    } elseif ($action === 'edit') {
        $group = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}product_groups WHERE id = $id", ARRAY_A);
        if ($group) {
            echo "<h2>ویرایش گروه</h2>
                  <form method='post' action=''>
                      <label for='edit_group_name'>نام گروه:</label>
                      <input type='text' id='edit_group_name' name='edit_group_name' value='{$group['name']}' required>
                      <input type='hidden' name='group_id' value='{$group['id']}'>
                      <button type='submit' name='submit_edit_group'>ذخیره تغییرات</button>
                  </form>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit_group'])) {
                $edit_group_name = sanitize_text_field($_POST['edit_group_name']);
                $group_id = intval($_POST['group_id']);
                $groups->edit_group($group_id, $edit_group_name);
                echo "<p>گروه با موفقیت ویرایش شد.</p>";
            }
        }
    }
}
?>

<div class="manage-groups">
    <h1>مدیریت گروه‌ها</h1>

    <!-- فرم افزودن گروه جدید -->
    <h2>افزودن گروه جدید</h2>
    <form method="post" action="">
        <?php wp_nonce_field('add_group_action', 'add_group_nonce'); ?>
        <label for="new_group_name">نام گروه:</label>
        <input type="text" id="new_group_name" name="new_group_name" required>
        <button type="submit" name="submit_new_group">ثبت گروه</button>
    </form>

    <!-- لیست گروه‌ها -->
    <h2>لیست گروه‌ها</h2>
    <table>
        <thead>
            <tr>
                <th>شناسه</th>
                <th>نام گروه</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $groups_list = $groups->get_groups();
            if (!empty($groups_list)) {
                foreach ($groups_list as $group) {
                    echo "<tr>";
                    echo "<td>{$group['id']}</td>";
                    echo "<td>{$group['name']}</td>";
                    echo "<td>
                            <a href='?page=my-accounting-plugin-manage-groups&action=edit&id={$group['id']}'>ویرایش</a> |
                            <a href='?page=my-accounting-plugin-manage-groups&action=delete&id={$group['id']}' onclick='return confirm(\"آیا مطمئن هستید؟\");'>حذف</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>هیچ گروهی یافت نشد.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>