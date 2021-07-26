<?php
/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Installer;

defined( 'ABSPATH' ) || exit;

class Install extends Installer {

	public static function install(): void {
		parent::install();

		do_action( 'wp_plugin_scaffold_install' );
	}

}
