<?php
/**
 * Plugin Name: WP Plugin Scaffold
 * Plugin URI:  http://www.ilmenite.io
 * Description: A WordPress plugin scaffold that we use at Bernskiold Media when developing client specific plugins.
 * Version:     1.0
 * Author:      Bernskiold Media
 * Author URI:  http://www.bernskioldmedia.com
 * Text Domain: wp-plugin-scaffold
 * Domain Path: /languages/
 *
 * **************************************************************************
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * **************************************************************************
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require 'vendor/autoload.php';

/**
 * Class WP_Plugin_Scaffold
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class WP_Plugin_Scaffold {


	/**
	 * Version
	 *
	 * @var string
	 */
	protected const VERSION = '1.0.0';

	/**
	 * Database Version
	 *
	 * @var string
	 */
	protected const DATABASE_VERSION = '1000';


	/**
	 * Plugin Class Instance Variable
	 *
	 * @var object
	 */
	protected static $_instance = null;

	/**
	 * Data Stores
	 *
	 * @var array
	 */
	protected $data_stores = [];

	/**
	 * Plugin Instantiator
	 *
	 * @return object
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.2
	 */
	private function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	private function __wakeup() {
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->admin_includes();
		$this->classes();
		$this->init_hooks();

		do_action( 'wp_plugin_scaffold_loaded' );

	}

	/**
	 * Hooks that are run on the time of init.
	 */
	private function init_hooks() {

		require_once 'includes/class-install.php';
		register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Install', 'install' ] );

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );

	}

	/**
	 * Initialize when WordPress is initialized.
	 *
	 * @return void
	 */
	public function init() {

		do_action( 'before_wp_plugin_scaffold_init' );

		// Localization support.
		$this->load_languages();

		do_action( 'wp_plugin_scaffold_init' );

	}

	/**
	 * Admin Includes
	 *
	 */
	public function admin_includes() {
		if ( is_admin() ) {


		}
	}

	/**
	 * Initialize Admin Only Features (admin_init)
	 */
	public function admin_init() {


	}

	/**
	 * Include various includes in the system.
	 */
	private function classes() {

		/**
		 * Interfaces
		 */
		require_once 'includes/interfaces/class-data-interface.php';
		require_once 'includes/interfaces/class-data-store-interface.php';
		require_once 'includes/interfaces/class-queries-interface.php';


		/**
		 * Abstracts
		 */
		require_once 'includes/abstracts/abstract-custom-post-type.php';
		require_once 'includes/abstracts/abstract-data.php';
		require_once 'includes/abstracts/abstract-taxonomy.php';
		require_once 'includes/abstracts/abstract-queries.php';

		/**
		 * API
		 */

		/**
		 * Data
		 */

		/**
		 * Data Stores
		 */
		require_once 'includes/data-stores/class-data-store-cpt.php';
		require_once 'includes/data-stores/class-data-store-taxonomy.php';

		$this->data_stores['cpt']      = new Data_Store_CPT();
		$this->data_stores['taxonomy'] = new Data_Store_Taxonomy();

		/**
		 * Other
		 */
		require_once 'includes/class-assets.php';
		require_once 'includes/class-log.php';

	}

	/**
	 * Load translations in the right order.
	 */
	public function load_languages() {

		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wp-plugin-scaffold' );

		unload_textdomain( 'wp-plugin-scaffold' );

		// Start checking in the main language dir.
		load_textdomain( 'wp-plugin-scaffold', WP_LANG_DIR . '/wp-plugin-scaffold/wp-plugin-scaffold-' . $locale . '.mo' );

		// Otherwise, load from the plugin.
		load_plugin_textdomain( 'wp-plugin-scaffold', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Get the path to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public static function get_path( $file = '' ) {
		return untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/' . $file;
	}

	/**
	 * Get View Template Path
	 *
	 * @param string $view_name
	 *
	 * @return string
	 */
	public static function get_view_path( $view_name ) {
		return self::get_path( '/views/' . $view_name . '.php' );
	}

	/**
	 * Get the URL to the plugin folder, or the specified
	 * file relative to the plugin folder home.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public static function get_url( $file = '' ) {
		$plugins_url = plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) );

		return untrailingslashit( $plugins_url ) . '/' . $file;
	}

	/**
	 * Get the URL to the assets folder, or the specified
	 * file relative to the assets folder home.
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	public static function get_assets_url( $file = '' ) {
		return self::get_url( '/assets/' . $file );
	}

	/**
	 * Get AJAX URL
	 *
	 * @return string
	 */
	public static function get_ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Get the Plugin's Version
	 *
	 * @return string
	 */
	public static function get_version() {
		return self::VERSION;
	}

	/**
	 * Get the database version number.
	 *
	 * @return string
	 */
	public static function get_database_version() {
		return self::DATABASE_VERSION;
	}

	/**
	 * Get Data Store Object
	 *
	 * @param string $key
	 *
	 * @return \stdClass
	 */
	public function get_data_store( $key ) {
		return $this->data_stores[ $key ];
	}

}

/**
 * Main Plugin Class Function
 *
 * @return object
 */
function wp_plugin_scaffold() {
	return WP_Plugin_Scaffold::instance();
}

// Initialize the class instance only once.
wp_plugin_scaffold();
