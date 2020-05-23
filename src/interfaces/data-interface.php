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

use BernskioldMedia\WP\PluginScaffold\Abstracts\Data_Store_WP;
use BernskioldMedia\WP\PluginScaffold\Data;

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
	 * Returns all data for this object.
	 *
	 * @return array
	 */
	public function get_data(): array;

	/**
	 * Get all the data keys for this object.
	 *
	 * @return array
	 */
	public function get_data_keys(): array;

	/**
	 * Get object type.
	 *
	 * @return string
	 */
	public static function get_object_type(): string;

	/**
	 * Get data store.
	 *
	 * @return Data_Store_WP
	 */
	public function get_data_store(): Data_Store_WP;

	/**
	 * Find or Create Object
	 *
	 * @param  string  $name
	 *
	 * @return Data
	 */
	public static function find_or_create( $name ): Data;

	/**
	 * Create a new Object
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return Data
	 */
	public static function create( $name, $args = [] ): Data;

	/**
	 * Update an object.
	 *
	 * @param  int  $object_id
	 *
	 * @return Data
	 */
	public static function update( $object_id ): Data;

	/**
	 * Delete an object.
	 *
	 * @return bool
	 */
	public static function delete(): bool;

}
