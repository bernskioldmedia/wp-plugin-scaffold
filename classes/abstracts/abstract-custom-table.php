<?php
/**
 * Abstract Custom Table Class
 *
 * The abstract custom table class helps us create a predicable
 * set of features to use when accessing custom table data stores.
 *
 * It extends the Data Store interface which ensures predicable
 * CRUD functions available for all types of data, from CPTs
 * to taxonomies or even custom tables.
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
 * Class Custom_Table
 *
 * @package BernskioldMedia\Client\PluginName
 */
abstract class Custom_Table implements Data_Store_Interface {

	/**
	 * Table Name
	 *
	 * @var string
	 */
	protected $table_name;

	/**
	 * Get table name
	 *
	 * @return string
	 */
	public function get_table_name() {
		return (string) $this->table_name;
	}

}
