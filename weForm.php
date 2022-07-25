<?php

/*
Plugin Name: weForm
Plugin URI:
Description: Contact Form or submit any data
Version: 1.1.1
Author: Anis Arronno
Author URI: https://wedevs.com
License: GPLv2 or later
Text Domain: we_form
 */

/**
 * Initialize the plugin tracker
 *
 * @return void
 */

require __DIR__ . '/vendor/autoload.php';

function appsero_init_tracker_weform()
{
    if (! class_exists('Appsero\Client')) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client('15c4f427-41b7-4207-ac7d-a8448f48ad88', 'weform', __FILE__);

    // Active insights
    $client->insights()->init();

    // Active automatic updater
    $client->updater();

    // Active license page and checker
    $args = array(
        'type'       => 'options',
        'menu_title' => 'weform',
        'page_title' => 'weform Settings',
        'menu_slug'  => 'weform_settings',
    );
    $client->license()->add_settings_page($args);
}

appsero_init_tracker_weform();



function we_form_init()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contacts';
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
