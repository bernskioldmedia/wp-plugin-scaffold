<?php

/**
 * Data Interface
 *
 * The data interface ensures that our data management classes
 * of a certain instance of an object behaves in the same way.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginBase
 * @since   1.0.0
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces;

\defined('ABSPATH') || exit;
/**
 * Interface DataInterface
 *
 * @package BernskioldMedia\WP\PluginBase
 */
interface DataInterface
{
    /**
     * Get ID
     */
    public function get_id() : int;
    /**
     * Set ID
     */
    public function set_id(int $id) : void;
    /**
     * Get object type key.
     */
    public static function get_object_type() : string;
    /**
     * Get data store class.
     */
    public static function get_data_store() : string;
    /**
     * Find an object.
     */
    public static function find(string $name);
    /**
     * Find or Create Object
     */
    public static function find_or_create(string $name);
    /**
     * Create a new Object
     */
    public static function create(string $name, array $args = []) : int;
    /**
     * Update an object.
     *
     * @return mixed
     */
    public static function update(int $object_id, array $args = []);
    /**
     * Delete an object.
     */
    public static function delete(int $object_id, bool $force_delete = \false) : bool;
}
