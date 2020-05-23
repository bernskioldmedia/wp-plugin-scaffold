<?php
/**
 * Data Interface
 *
 * The data interface ensures that our data management classes
 * of a certain instance of an object behaves in the same way.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface Data_Interface
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
interface Data_Interface {

	/**
	 * Get ID
	 *
	 * @return int
	 */
	public function get_id(): int;

	/**
	 * Set ID
	 *
	 * @param  int  $id
	 */
	public function set_id( int $id ): void;

	/**
	 * Get object type key.
	 *
	 * @return string
	 */
	public static function get_object_type(): string;

	/**
	 * Get data store class.
	 *
	 * @return string
	 */
	public static function get_data_store(): string;

	/**
	 * Find an object.
	 *
	 * @param  string  $name
	 *
	 * @return static
	 */
	public static function find( $name );

	/**
	 * Find or Create Object
	 *
	 * @param  string  $name
	 *
	 * @return static
	 */
	public static function find_or_create( $name );

	/**
	 * Create a new Object
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int
	 */
	public static function create( $name, $args = [] ): int;

	/**
	 * Update an object.
	 *
	 * @param  int    $object_id
	 * @param  array  $args
	 *
	 * @return mixed
	 */
	public static function update( $object_id, $args = [] );

	/**
	 * Delete an object.
	 *
	 * @param  int   $object_id
	 * @param  bool  $force_delete
	 *
	 * @return bool
	 */
	public static function delete( $object_id, $force_delete = false ): bool;

}
