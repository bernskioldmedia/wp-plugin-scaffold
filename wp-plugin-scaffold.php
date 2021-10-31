<?php
/**
 * Plugin Name: WP Plugin Scaffold
 * Plugin URI:  https://website.com
 * Description: A WordPress plugin scaffold that we use at Bernskiold Media when developing client specific plugins.
 * Version:     1.0.0
 * Author:      Bernskiold Media
 * Author URI:  https://bernskioldmedia.com
 * Text Domain: wp-plugin-scaffold
 * Domain Path: /languages/
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

use BernskioldMedia\WP\PluginScaffold\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloader
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
} else {
	throw new Exception( 'Autoload does not exist. Please run composer install --no-dev -o.' );
}

/**
 * Basic Constants
 */
define( 'WP_PLUGIN_SCAFFOLD_FILE_PATH', __FILE__ );

/**
 * Initialize and boot the plugin.
 *
 * @return Plugin
 */
function wp_plugin_scaffold() {
	return Plugin::instance();
}

wp_plugin_scaffold();
