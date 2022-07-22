<?php
add_filter('theme_page_templates', 'pt_add_page_template_to_dropdown');
add_filter('template_include', 'pt_change_page_template', 99);
add_action('wp_enqueue_scripts', 'pt_remove_style');
add_action('init', 'handle_form');

function validation($val)
{
    esc_html($val);
    sanitize_text_field($val);
    return $val;
}

function handle_form()
{
    if (! isset($_POST['action']) || 'wpdb_contact_form' !== $_POST['action']) {
        return;
    }
    if (
        ! isset($_POST['contact_form'])
        || ! wp_verify_nonce($_POST['contact_form'], '_contact_form_nonce')
    ) {
        $name=validation($_POST['name']);
        $email=validation($_POST['email']);
        $age=validation($_POST['age']);
        if (strlen($name)!=0 && strlen($email)!=0 && strlen($age)!=0) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wp_db';
            $result=$wpdb->insert($table_name, ['name'=>$name, 'email'=>$email, 'age'=>$age], ['%s', '%s', '%d']);
            if (is_wp_error($result)) {
                $result->get_error_message();
            }
        } else {
            echo 'Field is required';
        }
    }
}

/**
 * Add page templates.
 *
 * @param  array  $templates  The list of page templates
 *
 * @return array  $templates  The modified list of page templates
 */
function pt_add_page_template_to_dropdown($templates)
{
    $templates[plugin_dir_path(__FILE__) . 'templates/contact.php'] = __('Contact Page', 'text-domain');

    return $templates;
}

/**
 * Change the page template to the selected template on the dropdown
 *
 * @param $template
 *
 * @return mixed
 */
function pt_change_page_template($template)
{
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}

function pt_remove_style()
{
    // Change this "my-page" with your page slug
    if (is_page('contact-page')) {
        wp_enqueue_style('wpdb-demo-css', plugin_dir_url(__FILE__) . "assets/css/style.min.css", null, time());

        wp_enqueue_script('wpdb-demo-js', plugin_dir_url(__FILE__) . "assets/js/main.js", array( 'jquery' ), time(), true);
    }
}
