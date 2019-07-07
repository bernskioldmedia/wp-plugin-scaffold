<?php
/**
 * Abstract Data
 *
 * The abstract data functions sets extends our
 * data interface, which ensures we always have a base
 * set of functions ready.
 *
 * These functions are available whenever we create classes
 * that interface with data, be it Custom Post Types, full
 * custom database tables or taxonomies.
 *
 * We create these "helper classes" to make accessing
 * data very predicable and easy.
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
 * Abstract Class Data
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Data implements Data_Interface {

	/**
	 * ID for this object.
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Object Type
	 *
	 * @var string
	 */
	protected $object_type;

	/**
	 * Reference to the data store.
	 *
	 * @var object
	 */
	protected $data_store;

	/**
	 * Contains all the metadata for the object.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Data constructor.
	 *
	 * @param int|object|array $id
	 */
	public function __construct( $id ) {
	}

	/**
	 * Only store the object ID to avoid serializing the data object instance.
	 *
	 * @return array
	 */
	public function __sleep() {
		return [ 'id' ];
	}

	/**
	 * Re-run the constructor with the object ID.
	 *
	 * If the object no longer exists, remove the ID.
	 */
	public function __wakeup() {
		try {
			$this->__construct( absint( $this->id ) );
		} catch ( \Exception $e ) {
			$this->set_id( 0 );
		}
	}

	/**
	 * Change data to JSON format.
	 *
	 * @return string Data in JSON format.
	 */
	public function __toString() {
		return wp_json_encode( $this->get_data() );
	}

	/**
	 * Returns the unique ID for this object.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get Object Key
	 *
	 * @return string
	 */
	public function get_object_type() {
		return $this->object_type;
	}

	/**
	 * Get data store.
	 *
	 * @return object
	 */
	public function get_data_store() {
		return $this->data_store;
	}

	/**
	 * Set ID.
	 *
	 * @param $id
	 */
	public function set_id( $id ) {
		$this->id = absint( $id );
	}

	/**
	 * Returns all data for this object.
	 *
	 * @return array
	 */
	public function get_data() {
		return array_merge( [
			'id' => $this->get_id(),
		], $this->data );
	}

	/**
	 * Get all the data keys for this object.
	 *
	 * @return array
	 */
	public function get_data_keys() {
		return array_keys( $this->data );
	}

	/**
	 * Get Property
	 *
	 * @param string $field_key
	 *
	 * @return mixed|null
	 */
	protected function get_prop( $field_key ) {
		return get_field( $field_key, $this->get_id() );
	}

	/**
	 * Check is object exists.
	 *
	 * @return bool
	 */
	public function does_object_exist() {
		$object = $this->get_data_store()->read( $this->get_id() );

		if ( null === $object ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Check if user can work with the object in question.
	 * As type, this function takes: "edit", "delete" and "view".
	 *
	 * Defaults to current user if no user is given.
	 *
	 * @param string   $type
	 * @param null|int $user_id
	 *
	 * @return bool
	 */
	public function can_user( $type, $user_id = null ) {

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( 0 === $user_id ) {
			return false;
		}

		switch ( $type ) {

			case 'edit':
			case 'update':
				$capability = 'edit_' . $this->get_data_store()->get_singular_key();
				break;

			case 'delete':
				$capability = 'delete_' . $this->get_data_store()->get_singular_key();
				break;

			case 'view':
			case 'access':
			case 'read':
			default:
				$capability = 'read_' . $this->get_data_store()->get_singular_key();
				break;

		}

		$is_allowed = user_can( $user_id, $capability, $this->get_id() );

		return (bool) $is_allowed;

	}

	/**
	 * Set property
	 *
	 * @param string $field_key
	 * @param mixed  $new_value
	 *
	 * @return bool|int|mixed
	 */
	protected function set_prop( $field_key, $new_value ) {
		return update_field( $field_key, $new_value, $this->get_id() );
	}

}
