<?php
/*
Plugin Name: Salt Shaker
Plugin URI: https://wpcolt.com/
Description: A plugin that changes the WP salt values to enhance and strengthen WordPress security.
Version: 1.2
Author: WPColt
Author URI: https://wpcolt.com/
License: GPLv2 or later
Text Domain: salt-shaker
Domain Path: /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2016 WPColt.
*/
include_once(plugin_dir_path(__FILE__) . "_inc/loader.php");
$salt_shaker = new Salter();

function salt_shaker_load_plugin_textdomain() {
	load_plugin_textdomain( 'salt-shaker', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'salt_shaker_load_plugin_textdomain' );


/********* Start Freemius ***********/
// Create a helper function for easy SDK access.
function salt_shaker_fs() {
	global $salt_shaker_fs;

	if ( ! isset( $salt_shaker_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$salt_shaker_fs = fs_dynamic_init( array(
			'id'                  => '1476',
			'slug'                => 'salt-shaker',
			'type'                => 'plugin',
			'public_key'          => 'pk_5c4b5401b7ec26fbc64a272f6db03',
			'is_live'             => false,
			'is_premium'          => false,
			'has_addons'          => false,
			'has_paid_plans'      => false,
			'menu'                => array(
				'slug'           => 'salt_shaker',
				'account'        => false,
				'contact'        => false,
				'support'        => false,
				'parent'         => array(
					'slug' => 'tools.php',
				),
			),
		) );
	}

	return $salt_shaker_fs;
}

// Init Freemius.
salt_shaker_fs();
// Signal that SDK was initiated.
do_action( 'salt_shaker_fs_loaded' );

//Welcome message after update
function salt_shaker_fs_custom_connect_message_on_update(
	$message,
	$user_first_name,
	$plugin_title,
	$user_login,
	$site_link,
	$freemius_link
) {
	return sprintf(
		__fs( 'hey-x' ) . '<br>' .
		__( 'Please help us improve %2$s! If you opt-in, we\'ll collect some usage data that will help us improve the plugin. If you skip this, that\'s okay! %2$s will still work just fine.', 'salt-shaker' ),
		$user_first_name,
		'<b>' . $plugin_title . '</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

salt_shaker_fs()->add_filter('connect_message_on_update', 'salt_shaker_fs_custom_connect_message_on_update', 10, 6);

salt_shaker_fs()->add_action('after_uninstall', 'salt_shaker_fs_uninstall_cleanup');