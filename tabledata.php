<?php

require_once "weFormClass.php";

function datatable_admin_page()
{
    add_menu_page(
        __('We Form', 'we_form'),
        __('We Form', 'we_form'),
        'manage_options',
        'we-form',
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
    $table_name = $wpdb->prefix . 'contacts';
    $results = $wpdb->get_results("select id, name, email, age, sex, created_at from {$table_name}");
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
    } elseif ('sex' == $orderby) {
        if ('asc' == $order) {
            usort($data, function ($item1, $item2) {
                return $item2['sex'] <=> $item1['sex'];
            });
        } else {
            usort($data, function ($item1, $item2) {
                return $item1['sex'] <=> $item2['sex'];
            });
        }
    } elseif ('email' == $orderby) {
        if ('asc' == $order) {
            usort($data, function ($item1, $item2) {
                return $item2['email'] <=> $item1['email'];
            });
        } else {
            usort($data, function ($item1, $item2) {
                return $item1['email'] <=> $item2['email'];
            });
        }
    } elseif ('created_at' == $orderby) {
        if ('asc' == $order) {
            usort($data, function ($item1, $item2) {
                return $item2['created_at'] <=> $item1['created_at'];
            });
        } else {
            usort($data, function ($item1, $item2) {
                return $item1['created_at'] <=> $item2['created_at'];
            });
        }
    }
    $table->set_data($data);

    $table->prepare_items(); ?>
<div class="wrap">
    <h2>
        <?php _e("All Contact", "we_form"); ?>
    </h2>

    <?php if (isset($_GET['contact-deleted']) && $_GET['contact-deleted'] == 'true') { ?>
    <div class="notice notice-success">
        <p><?php _e('Contact has been deleted successfully!', 'wedevs-academy'); ?>
        </p>
    </div>
    <?php } ?>

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





function wc_delete_contact($id)
{
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'contacts',
        [ 'id' => $id ],
        [ '%d' ]
    );
}

function delete_contact()
{
    if (! wp_verify_nonce($_REQUEST['_wpnonce'], 'wc-delete-contact')) {
        wp_die('Are you cheating?');
    }

    if (! current_user_can('manage_options')) {
        wp_die('Are you cheating?');
    }

    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

    if (wc_delete_contact($id)) {
        $redirected_to = admin_url('admin.php?page=we-form&contact-deleted=true');
    } else {
        $redirected_to = admin_url('admin.php?page=we-form&contact-deleted=false');
    }

    wp_redirect($redirected_to);
    exit;
}


add_action('admin_post_wc-delete-contact', 'delete_contact');
