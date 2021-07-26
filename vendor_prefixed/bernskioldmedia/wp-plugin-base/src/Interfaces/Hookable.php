<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces;

/**
 * Interface Hookable
 *
 * @package BernskioldMedia\WP\PluginBase\Interfaces
 */
interface Hookable
{
    /**
     * Hookable classes must implement a standardized hooks function
     * that can be called when booted.
     */
    public static function hooks() : void;
}
