<?php
/**
 * Installer
 *
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Install
 *
 * @package BernskioldMedia\Client\PluginName
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
