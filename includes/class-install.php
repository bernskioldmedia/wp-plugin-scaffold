<?php
/**
 * Installer
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Install
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Install {

	/**
	 * Hooks
	 */
	public static function hooks() {

	}

	/**
	 * Main Install Process
	 */
	public static function install() {

		self::scheduled_tasks();

	}

	/**
	 * Scheduled Tasks
	 */
	public static function scheduled_tasks() {

	}

}
