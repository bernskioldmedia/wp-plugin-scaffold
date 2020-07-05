<?php

namespace BernskioldMedia\WP\PluginScaffold;

use BernskioldMedia\WP\PluginBase\Abstracts\Base_Plugin;
use BernskioldMedia\WP\PluginBase\Traits\Has_Data_Stores;
use BernskioldMedia\WP\PluginScaffold\Rest\REST_Example;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Plugin extends Base_Plugin {

	use Has_Data_Stores;

	/**
	 * Version
	 *
	 * @var string
	 */
	protected static $version = '1.0.0';

	/**
	 * Database Version
	 *
	 * @var string
	 */
	protected static $database_version = '1000';

	/**
	 * Plugin Textdomain
	 *
	 * @var string
	 */
	protected static $textdomain = 'bm-block-library';

	/**
	 * Main plugin file path.
	 *
	 * @var string
	 */
	protected static $plugin_file_path = WP_PLUGIN_SCAFFOLD_FILE_PATH;

	/**
	 * A list of all data store classes to register.
	 *
	 * @var string[]
	 */
	public static $data_stores = [
		Data_Stores\Taxonomy::class,
		Data_Stores\CPT::class,
	];

	/**
	 * A list of all data store classes to register.
	 *
	 * @var string[]
	 */
	public static $rest_endpoints = [
		REST_Example::class,
	];

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		register_activation_hook( WP_PLUGIN_SCAFFOLD_FILE_PATH, [ Install::class, 'install' ] );

		$this->boot_data_stores();

	}

	/**
	 * Hooks that are run on the time of init.
	 */
	protected function init_hooks(): void {
		parent::init_hooks();

		Assets::hooks();

		do_action( 'wp_plugin_scaffold_init' );
	}

}
