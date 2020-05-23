<?php
/**
 * Data Store Interface
 *
 * Our data stores should behave in the same way, regardless
 * of whether they are CPTs, Taxonomies or Custom Tables.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Interfaces;

defined( 'ABSPATH' ) || exit;

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
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int|bool
	 */
	public function create( $name, $args );

	/**
	 * Update an item with new values.
	 *
	 * @param  int           $object_id
	 * @param  array|string  $args
	 *
	 * @return mixed
	 */
	public function update( $object_id, $args );

	/**
	 * Delete an item.
	 *
	 * @param  int  $object_id
	 *
	 * @return mixed
	 */
	public function delete( $object_id );

}
