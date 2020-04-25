<?php
/**
 * Plugin Name: WP Plugin Scaffold
 * Plugin URI:  https://bernskioldmedia.com
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

require_once 'autoloader.php';
require 'vendor/autoload.php';

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
