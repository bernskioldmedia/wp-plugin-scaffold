<?php

namespace BernskioldMedia\WP\PluginScaffold;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\BasePlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {

	protected static string $slug             = 'wp_plugin_scaffold';
	protected static string $version          = 'VERSION';
	protected static string $textdomain       = 'wp-plugin-scaffold';
	protected static string $plugin_file_path = WP_PLUGIN_SCAFFOLD_FILE_PATH;

	protected static array $boot = [
		Assets::class,
	];
}
