<?php
/**
 * Data Store Interface
 *
 * Our data stores should behave in the same way, regardless
 * of whether they are CPTs, Taxonomies or Custom Tables.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package WP_Plugin_Scaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Data_Store_Interface
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
interface Data_Store_Interface {

	/**
	 * Get the object key.
	 *
	 * @return mixed
	 */
	public function get_key();

	/**
	 * Create an item.
	 *
	 * @param array $args
	 *
	 * @return int|bool
	 */
	public function create( $args );

	/**
	 * Get an item.
	 *
	 * @param int $object_id
	 *
	 * @return mixed
	 */
	public function read( $object_id );

	/**
	 * Update an item with new values.
	 *
	 * @param int          $object_id
	 * @param array|string $args
	 *
	 * @return mixed
	 */
	public function update( $object_id, $args );

	/**
	 * Delete an item.
	 *
	 * @param int $object_id
	 *
	 * @return mixed
	 */
	public function delete( $object_id );

}
