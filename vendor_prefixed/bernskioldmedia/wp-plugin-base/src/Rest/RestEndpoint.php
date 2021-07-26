<?php

/**
 * Abstract REST API Endpoint Class
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginBase
 * @since   1.0.0
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Rest;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces\RestEndpointInterface;
\defined('ABSPATH') || exit;
/**
 * Class RestEndpoint
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class RestEndpoint extends \WP_REST_Controller implements RestEndpointInterface
{
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
     */
    protected $namespace = 'wp_plugin_scaffold';
    /**
     * Version
     */
    protected string $version = '1';
    /**
     * REST Routes
     */
    protected array $routes = [];
    /**
     * Setup Extension
     */
    public function load() : void
    {
        $this->setup_routes();
        $this->init();
    }
    /**
     * Initialize the endpoint.
     */
    public function init() : void
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
    /**
     * Register the routes.
     */
    protected abstract function setup_routes() : void;
    /**
     * Register REST Routes
     *
     * @see https://developer.wordpress.org/reference/functions/register_rest_route/
     */
    public function register_routes() : void
    {
        foreach ($this->get_routes() as $route => $args) {
            register_rest_route($this->get_namespace(), $route, $args);
        }
    }
    /**
     * Get sanitized URL param filter value.
     */
    protected function get_filter_value(\WPPS_Vendor\WP_REST_Request $request, string $key) : ?string
    {
        return isset($request[$key]) ? wp_strip_all_tags($request[$key]) : null;
    }
    /**
     * Add a route
     *
     * @return static
     */
    protected function add_route(string $route, array $args)
    {
        $this->routes[$route] = $args;
        return $this;
    }
    /**
     * Get Endpoint Routes
     */
    protected function get_routes() : array
    {
        return $this->routes;
    }
    /**
     * Get the Namespace
     */
    protected function get_namespace() : string
    {
        return $this->namespace . '/v' . $this->version;
    }
    /**
     * Public level permissions access.
     */
    public function has_public_access() : bool
    {
        return \true;
    }
}
