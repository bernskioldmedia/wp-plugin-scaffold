<?php

/**
 * REST Endpoint Interface
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginBase
 * @since   1.0.0
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces;

\defined('ABSPATH') || exit;
/**
 * Interface RestEndpointInterface
 *
 * @package BernskioldMedia\WP\PluginBase
 */
interface RestEndpointInterface
{
    /**
     * Register Routes
     */
    public function register_routes() : void;
}
