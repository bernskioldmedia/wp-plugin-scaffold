<?php
/**
 * REST Endpoint Interface
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface REST_Endpoint_Interface
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
interface REST_Endpoint_Interface {

	/**
	 * Register Routes
	 */
	public function register_routes();

}
