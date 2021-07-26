<?php
/**
 * Handles the loading of scripts and styles for the
 * theme through the proper enqueuing methods.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 **/

namespace BernskioldMedia\WP\PluginScaffold;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\AssetManager;

defined( 'ABSPATH' ) || exit;

class Assets extends AssetManager {

	protected static array $public_scripts = [];
	protected static array $admin_scripts  = [];

	protected static array $public_styles = [];
	protected static array $admin_styles  = [];

	public static function enqueue_public_scripts(): void {
		// @todo Enqueue here. Registration already done automatically.
	}

	public static function enqueue_admin_scripts(): void {
		// @todo Enqueue here. Registration already done automatically.
	}

	public static function enqueue_public_styles(): void {
		// @todo Enqueue here. Registration already done automatically.
	}

	public static function enqueue_admin_styles(): void {
		// @todo Enqueue here. Registration already done automatically.
	}
}
