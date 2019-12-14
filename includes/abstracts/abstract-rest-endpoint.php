<?php
/**
 * Abstract REST API Endpoint Class
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class REST_Endpoint
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class REST_Endpoint implements REST_Endpoint_Interface {

	/**
	 * Alias for GET method.
	 */
	const READABLE = 'GET';

	/**
	 * Alias for POST method.
	 */
	const CREATABLE = 'POST';

	/**
	 * Alias for PUT, POST, PATCH method.
	 */
	const EDITABLE = 'PUT, POST, PATCH';

	/**
	 * Alias for DELETE method
	 */
	const DELETABLE = 'DELETE';

	/**
	 * Alias for all methods together.
	 */
	const ALL = 'GET, POST, PUT, PATCH, DELETE';

	/**
	 * Namespace
	 *
	 * @var string
	 */
	protected $namespace = 'wp_plugin_scaffold';

	/**
	 * Version
	 *
	 * @var string
	 */
	protected $version = '1';

	/**
	 * REST Routes
	 *
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Setup Extension
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		$this->setup_routes();
	}

	/**
	 * Register the routes.
	 */
	abstract protected function setup_routes();

	/**
	 * Register REST Routes
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_rest_route/
	 */
	public function register_routes() {
		foreach ( $this->get_routes() as $route => $args ) {
			register_rest_route( $this->get_namespace(), $route, $args );
		}
	}

	/**
	 * Get sanitized URL param filter value.
	 *
	 * @param \WP_REST_Request $request
	 * @param string           $name
	 *
	 * @return string|null
	 */
	protected function get_filter_value( $request, $name ) {
		$value = isset( $request[ $name ] ) ? wp_strip_all_tags( $request[ $name ] ) : null;

		return $value;
	}

	/**
	 * Add a route
	 *
	 * @param string $route NB. Prefix with /
	 * @param array  $args
	 */
	protected function add_route( $route, $args ) {
		$this->routes[ $route ] = $args;
	}

	/**
	 * Get Endpoint Routes
	 *
	 * @return array
	 */
	protected function get_routes() {
		return $this->routes;
	}

	/**
	 * Get the Namespace
	 *
	 * @return string
	 */
	protected function get_namespace() {
		return $this->namespace . '/v' . $this->version;
	}

	/**
	 * Public level permissions access.
	 *
	 * @return bool
	 */
	public function has_public_access() {
		return true;
	}

}
