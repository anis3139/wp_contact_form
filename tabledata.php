<?php

require_once "weFormClass.php";

function datatable_admin_page()
{
    add_menu_page(
        __('We Form', 'we_form'),
        __('We Form', 'we_form'),
        'manage_options',
        'datatable',
        'datatable_display_table',
        'dashicons-email-alt2',
        25
    );
}

function datatable_search_by_name($item)
{
    $name        = strtolower($item['name']);
    $search_name = sanitize_text_field($_REQUEST['s']);
    if (strpos($name, $search_name) !== false) {
        return true;
    }

    return false;
}

function datatable_filter_sex($item)
{
    $sex = $_REQUEST['filter_s']??'all';
    if ('all'==$sex) {
        return true;
    } else {
        if ($sex==$item['sex']) {
            return true;
        }
    }
    return false;
}

function datatable_display_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wp_db';
    $results = $wpdb->get_results("select id, name, email, age, sex from {$table_name}");
    $data=json_decode(json_encode($results), true);

    $orderby = $_REQUEST['orderby'] ?? '';
    $order   = $_REQUEST['order'] ?? '';
    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
        $data = array_filter($data, 'datatable_search_by_name');
    }

    if (isset($_REQUEST['filter_s']) && !empty($_REQUEST['filter_s'])) {
        $data = array_filter($data, 'datatable_filter_sex');
    }

    $table = new We_Form_Table();
    if ('age' == $orderby) {
        if ('asc' == $order) {
            usort($data, function ($item1, $item2) {
                return $item2['age'] <=> $item1['age'];
            });
        } else {
            usort($data, function ($item1, $item2) {
                return $item1['age'] <=> $item2['age'];
            });
        }
    } elseif ('name' == $orderby) {
        if ('asc' == $order) {
            usort($data, function ($item1, $item2) {
                return $item2['name'] <=> $item1['name'];
            });
        } else {
            usort($data, function ($item1, $item2) {
                return $item1['name'] <=> $item2['name'];
            });
        }
    }
    $table->set_data($data);

    $table->prepare_items(); ?>
<div class="wrap">
    <h2><?php _e("All Contact", "we_form"); ?>
    </h2>
    <form method="GET">
        <?php
            $table->search_box('search', 'search_id');
    $table->display(); ?>
        <input type="hidden" name="page"
            value="<?php echo $_REQUEST['page']; ?>">
    </form>
</div>
<?php
}

add_action("admin_menu", "datatable_admin_page");
