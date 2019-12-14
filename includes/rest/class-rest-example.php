<?php
/**
 * REST Endpoint: Example
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class REST_Example
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class REST_Example extends REST_Endpoint {

	public function __construct() {
		parent::__construct();
	}

	protected function setup_routes() {
		$this->add_route( '/example', [
			'methods'             => $this::READABLE,
			'callback'            => [ $this, 'get_example' ],
			'permission_callback' => [ $this, 'has_public_access' ],
		] );
	}

	/**
	 * Get Example Data
	 *
	 * @return array
	 */
	public function get_example() {
		return [];
	}

}
