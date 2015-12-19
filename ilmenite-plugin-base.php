<?php
/*
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
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Ilmenite_PB {

	/**
	 * Plugin URL
	 *
	 * @var string
	 */
	public $plugin_url = '';

	/**
	 * Plugin Directory Path
	 *
	 * @var string
	 */
	public $plugin_dir = '';

	/**
	 * Plugin Version Number
	 *
	 * @var string
	 */
	public $plugin_version = '';


	/**
	 * @var The single instance of the class
	 */
	protected static $_instance = null;

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
	private function __clone() {}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	private function __wakeup() {}

	/**
	 * Constructor
	 */
	public function __construct() {

		// Set Plugin Version
		$this->plugin_version = '1.0';

		// Set plugin Directory
		$this->plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );

		// Set Plugin URL
		$this->plugin_url = untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );

		// Load Translations
		add_action( 'plugins_loaded', array( $this, 'languages' ) );

		// Load Taxonomy "Services"
		require_once( 'inc/taxonomies/class-ilpb-tax-examples.php' );
		$this->tax_example = new ILPB_Tax_Examples;

		// Load Custom Post Type "Testimonials"
		require_once( 'inc/post-types/class-ilpb-cpt-examples.php' );
		$this->cpt_example = new ILPB_CPT_Examples;

		// Run Activation Hook
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

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

		// Initialize all the CPTs and flush permalinks
		flush_rewrite_rules();

	}

	/**
	 * Get the Plugin's Directory Path
	 *
	 * @return string
	 */
	public function get_plugin_dir() {
		return $this->plugin_dir;
	}

	/**
	 * Get the Plugin's Directory URL
	 *
	 * @return string
	 */
	public function get_plugin_url() {
		return $this->plugin_url;
	}

	/**
	 * Get the Plugin's Version
	 *
	 * @return string
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Get the Plugin's Asset Directory URL
	 *
	 * @return string
	 */
	public function get_plugin_assets_uri() {
		return $this->plugin_url . '/assets/';
	}

}

function Ilmenite_PB() {
    return Ilmenite_PB::instance();
}

// Initialize the class instance only once
Ilmenite_PB();