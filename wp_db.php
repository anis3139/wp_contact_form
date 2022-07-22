<?php

/*
Plugin Name: WPDB
Plugin URI:
Description: Demonstration of WPDB Methods
Version: 1.0.0
Author: Anis Arronno
Author URI: https://wedevs.com
License: GPLv2 or later
Text Domain: wpdb
 */


function wpdb_init()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'wp_db';
    $sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(250),
			email VARCHAR(250),
            age INT,
			PRIMARY KEY (id)
	);";
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);

    $persons = array(
        array(
            'name'  => 'David',
            'email' => 'david@doe.com',
            'age'   => 30,
        ),
        array(
            'name'  => 'Brenda',
            'email' => 'brenda@doe.com',
            'age'   => 31,
        ),
    );

    foreach ($persons as $person) {
        $wpdb->insert($table_name, $person);
    }
}

register_activation_hook(__FILE__, "wpdb_init");

require_once('page.php');
require_once "tabledata.php";
