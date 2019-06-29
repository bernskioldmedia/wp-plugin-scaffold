<?php
/**
 * Data Interface
 *
 * The data interface ensures that our data management classes
 * of a certain instance of an object behaves in the same way.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package Ilmenite_PB
 * @since   1.0.0
 */

namespace BernskioldMedia\Client\PluginName;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Data_Interface
 *
 * @package BernskioldMedia\Client\PluginName
 */
interface Data_Interface {

	/**
	 * Get ID
	 *
	 * @return int
	 */
	public function get_id();

	/**
	 * Get object type.
	 *
	 * @return string
	 */
	public function get_object_type();

	/**
	 * Get data store.
	 *
	 * @return string
	 */
	public function get_data_store();

}
