<?php
/**
 * Plugin Name: Ilmenite Plugin Base
 * Plugin URI:  http://www.ilmenite.io
 * Description: A WordPress plugin base that we use at Bernskiold Media when developing client specific plugins.
 * Version:     1.0
 * Author:      Bernskiold Media
 * Author URI:  http://www.bernskioldmedia.com
 * Text Domain: PLUGINTEXTDOMAINHERE
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
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include Autoloader.
require 'vendor/autoload.php';

/**
 * Class Ilmenite_PB
 *
 * @package BernskioldMedia\Client\PluginName
 */
class Ilmenite_PB {


	/**
	 * CFN Master Version
	 *
	 * @var string
	 */
	protected static $version = '1.0.0';

	/**
	 * CFN Master Database Version
	 *
	 * @var string
	 */
	protected static $database_version = '1000';


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

		do_action( 'ilmenite_pb_loaded' );

	}

	/**
	 * Hooks that are run on the time of init.
	 */
	private function init_hooks() {

		require_once 'classes/class-install.php';
		register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Install', 'install' ] );

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'admin_init' ] );

	}

	/**
	 * Initialize CFN Master when WordPress is initialized.
	 *
	 * @return void
	 */
	public function init() {

		do_action( 'before_ilmenite_pb_init' );

		// Localization support.
		$this->load_languages();

		do_action( 'ilmenite_pb_init' );

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
		require_once 'classes/interfaces/class-data-interface.php';
		require_once 'classes/interfaces/class-data-store-interface.php';
		require_once 'classes/interfaces/class-queries-interface.php';


		/**
		 * Abstracts
		 */
		require_once 'classes/abstracts/abstract-custom-post-type.php';
		require_once 'classes/abstracts/abstract-data.php';
		require_once 'classes/abstracts/abstract-taxonomy.php';
		require_once 'classes/abstracts/abstract-queries.php';

		/**
		 * API
		 */

		/**
		 * Data
		 */


		/**
		 * Data Stores
		 */
		require_once 'classes/data-stores/class-data-store-cpt.php';
		require_once 'classes/data-stores/class-data-store-taxonomy.php';

		$this->data_stores['cpt']      = new Data_Store_CPT();
		$this->data_stores['taxonomy'] = new Data_Store_Taxonomy();

		/**
		 * Other
		 */
		require_once 'classes/class-log.php';

	}

	/**
	 * Load translations in the right order.
	 */
	public function load_languages() {

		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'PLUGINTEXTDOMAINHERE' );

		unload_textdomain( 'PLUGINTEXTDOMAINHERE' );

		// Start checking in the main language dir.
		load_textdomain( 'PLUGINTEXTDOMAINHERE', WP_LANG_DIR . '/ilmenitepb/ilmenitepb-' . $locale . '.mo' );

		// Otherwise, load from the plugin.
		load_plugin_textdomain( 'PLUGINTEXTDOMAINHERE', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Get the Plugin's Directory Path
	 *
	 * @return string
	 */
	public static function get_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get View Template Path
	 *
	 * @param string $view_name
	 *
	 * @return string
	 */
	public static function get_view_path( $view_name ) {
		return self::get_path() . '/views/' . $view_name . '.php';
	}

	/**
	 * Get the Plugin's Directory URL
	 *
	 * @return string
	 */
	public static function get_url() {
		return untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
	}

	/**
	 * Get the Plugin's Asset Directory URL
	 *
	 * @return string
	 */
	public static function get_assets_url() {
		return self::get_url() . '/assets/';
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
		return self::$version;
	}

	/**
	 * Get the database version number.
	 *
	 * @return string
	 */
	public static function get_database_version() {
		return self::$database_version;
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
function ilmenite_pb() {
	return Ilmenite_PB::instance();
}

// Initialize the class instance only once.
ilmenite_pb();
