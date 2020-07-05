<?php
/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

use BernskioldMedia\WP\PluginBase\Abstracts\Installer;
use BernskioldMedia\WP\PluginScaffold\Roles\User_Roles;

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Install extends Installer {

	/**
	 * Main Install Process
	 */
	public static function install(): void {
		parent::install();

		User_Roles::install();

		do_action( 'wp_plugin_scaffold_install' );

	}

}
