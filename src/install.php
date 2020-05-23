<?php
/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

use BernskioldMedia\WP\PluginScaffold\Roles\User_Roles;

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Install {

	/**
	 * Main Install Process
	 */
	public static function install() {

		self::scheduled_tasks();
		User_Roles::boot();

		do_action( 'wp_plugin_scaffold_install' );

	}

	/**
	 * Scheduled Tasks
	 */
	public static function scheduled_tasks() {

	}

}
