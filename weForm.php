<?php

/*
Plugin Name: weForm
Plugin URI:
Description: Contact Form or submit any data
Version: 1.0.0
Author: Anis Arronno
Author URI: https://wedevs.com
License: GPLv2 or later
Text Domain: we_form
 */


function we_form_init()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact';
    $sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(250) NOT NULL,
			email VARCHAR(250) NOT NULL,
			sex ENUM('M', 'F', 'O') DEFAULT 'M',
            age INT NOT NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
	);";
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);
}

register_activation_hook(__FILE__, "we_form_init");

function we_form_load_textdomain()
{
    load_plugin_textdomain('we_form', false, dirname(__FILE__) . "/languages");
}

add_action("plugins_loaded", "we_form_load_textdomain");
require_once('page.php');
require_once "tabledata.php";

