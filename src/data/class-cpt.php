<?php
/**
 * CPT Data
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Data;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class CPT
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class CPT extends Data {

	/**
	 * Get the ID is passed, otherwise the item is new and empty.
	 *
	 * @param int|array $object_id Item to init.
	 */
	public function __construct( $object_id = 0 ) {

		parent::__construct( $object_id );
		$this->data_store = wp_plugin_scaffold()->get_data_store( 'cpt' );

		if ( is_numeric( $object_id ) && $object_id > 0 ) {
			$this->set_id( $object_id );
		} elseif ( is_array( $object_id ) && isset( $object_id['text'] ) ) {
			$new_id = $this->get_data_store()->create( $object_id );
			$this->set_id( $new_id );
		}

	}

	/**
	 * Get Customer Name
	 *
	 * @return string
	 */
	public function get_name() {
		return get_the_title( $this->get_id() );
	}

}
