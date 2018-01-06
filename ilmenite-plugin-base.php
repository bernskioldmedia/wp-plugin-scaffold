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

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Exit if accessed directly.
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
	 * Post Types
	 *
	 * @var \stdClass
	 */
	public $post_types;

	/**
	 * Taxonomies
	 *
	 * @var \stdClass
	 */
	public $taxonomies;

	/**
	 * Monolog Logger
	 *
	 * @var Logger
	 */
	public $logger;

	/**
	 * Plugin Version Number
	 *
	 * @var string
	 */
	const PLUGIN_VERSION = '1.0.0';


	/**
	 * Plugin Class Instance Variable
	 *
	 * @var object
	 */
	protected static $_instance = null;

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

		// Activate the logger.
		$this->setup_logger();

		// Load Translations.
		add_action( 'plugins_loaded', array( $this, 'languages' ) );

		// Load Custom Post Types.
		$this->load_post_types();

		// Load Taxonomies.
		$this->load_taxonomies();

		// Run Activation Hook.
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

	}

	/**
	 * Setup Logger
	 *
	 * Creates a logger for the plugin using Monolog.
	 */
	protected function setup_logger() {

		// Set the log path.
		$log_path = WP_CONTENT_DIR . '/logs/ilmenite-plugin-base.log';

		// Create the logger.
		$this->logger = new Logger( 'IlmenitePluginBase' );

		// Define the log level depending on environment.
		if ( ( function_exists( 'env' ) && 'development' === env( 'WP_ENV' ) ) || true === WP_DEBUG ) {
			$log_level = LOGGER::DEBUG;
		} else {
			$log_level = LOGGER::ERROR;
		}

		// Set up the saving.
		$this->logger->pushHandler( new StreamHandler( $log_path, $log_level ) );

	}

	/**
	 * Load Custom Post Type
	 */
	protected function load_post_types() {

		$this->post_types = new \stdClass();

		// Load Custom Post Type "Testimonials".
		require_once( 'includes/classes/post-types/class-cpt-examples.php' );
		$this->post_types->examples = new CPT_Examples;

	}

	/**
	 * Load Taxonomies
	 */
	protected function load_taxonomies() {

		$this->taxonomies = new \stdClass();

		// Load Taxonomy "Services".
		require_once( 'includes/classes/taxonomies/class-tax-examples.php' );
		$this->taxonomies->examples = new Tax_Examples;

	}

	/**
	 * Load Translations
	 */
	public function languages() {

		load_plugin_textdomain( 'PLUGINTEXTDOMAINHERE', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Activation Trigger
	 *
	 * This code is run automatically when the WordPress
	 * plugin is activated.
	 */
	public function plugin_activation() {

		// Initialize all the CPTs and flush permalinks.
		flush_rewrite_rules();

	}

	/**
	 * Get the Plugin's Directory Path
	 *
	 * @return string
	 */
	public static function get_plugin_dir() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the Plugin's Directory URL
	 *
	 * @return string
	 */
	public static function get_plugin_url() {
		return untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
	}

	/**
	 * Get the Plugin's Asset Directory URL
	 *
	 * @return string
	 */
	public static function get_plugin_assets_url() {
		return self::get_plugin_url() . '/assets/';
	}

	/**
	 * Get the Plugin's Version
	 *
	 * @return string
	 */
	public static function get_plugin_version() {
		return self::PLUGIN_VERSION;
	}

}

/**
 * Main Plugin Class Function
 *
 * @return object
 */
function Ilmenite_PB() {
	return Ilmenite_PB::instance();
}

// Initialize the class instance only once.
Ilmenite_PB();
