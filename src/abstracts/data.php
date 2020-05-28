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
 * @package BernskioldMedia\WP\Event
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

use BernskioldMedia\WP\PluginScaffold\Interfaces\Data_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class Data
 *
 * @package BernskioldMedia\WP\Event
 */
abstract class Data implements Data_Interface {

	/**
	 * ID for this object.
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Reference to the data store.
	 *
	 * @var string
	 */
	protected static $data_store;

	/**
	 * Data constructor.
	 *
	 * @param  int|object|array  $id
	 */
	public function __construct( $id = 0 ) {
		if ( is_numeric( $id ) && $id > 0 ) {
			$this->set_id( $id );
		}
	}

	/**
	 * Only store the object ID to avoid serializing the data object instance.
	 *
	 * @return array
	 */
	public function __sleep(): array {
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
	 * Returns the unique ID for this object.
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->id;
	}

	/**
	 * Get Object Key
	 *
	 * @return string
	 */
	public static function get_object_type(): string {
		return static::get_data_store()::get_key();
	}

	/**
	 * Get data store.
	 *
	 * @return string
	 */
	public static function get_data_store(): string {
		return static::$data_store;
	}

	/**
	 * Set ID.
	 *
	 * @param  int  $id
	 */
	public function set_id( $id ): void {
		$this->id = absint( $id );
	}

	/**
	 * Get Property
	 *
	 * @param  string  $field_key
	 *
	 * @return mixed|null
	 */
	protected function get_prop( string $field_key ) {
		return get_field( $field_key, $this->get_id() );
	}

	/**
	 * Check if user can work with the object in question.
	 * As type, this function takes: "edit", "delete" and "view".
	 *
	 * Defaults to current user if no user is given.
	 *
	 * @param  string    $type
	 * @param  null|int  $user_id
	 *
	 * @return bool
	 */
	public function can_user( string $type, ?int $user_id = null ): bool {

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( 0 === $user_id ) {
			return false;
		}

		switch ( $type ) {

			case 'edit':
			case 'update':
				$capability = 'edit_' . static::get_data_store()::get_key();
				break;

			case 'delete':
				$capability = 'delete_' . static::get_data_store()::get_key();
				break;

			case 'view':
			case 'access':
			case 'read':
			default:
				$capability = 'read_' . static::get_data_store()::get_key();
				break;

		}

		return user_can( $user_id, $capability, $this->get_id() );

	}

	/**
	 * Find an object.
	 *
	 * @param  string  $name
	 *
	 * @return static|null
	 */
	public static function find( $name ) {

		$id = static::get_data_store()::does_object_exist( $name );

		if ( ! $id ) {
			return null;
		}

		return new static( $id );

	}

	/**
	 * Find or Create Object
	 *
	 * @param  string  $name
	 *
	 * @return static
	 */
	public static function find_or_create( $name ) {

		$id = static::get_data_store()::does_object_exist( $name );

		if ( $id ) {
			return new static( $id );
		}

		$new_id = static::create( $name );

		return new static( $new_id );

	}

	/**
	 * Set property
	 *
	 * @param  string  $field_key
	 * @param  mixed   $new_value
	 *
	 * @return bool|int|mixed
	 */
	protected function set_prop( string $field_key, $new_value ) {
		return update_field( $field_key, $new_value, $this->get_id() );
	}

	/**
	 * Create an item.
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int|null
	 */
	public static function create( $name, $args = [] ): int {
		return static::get_data_store()::create( $name, $args );
	}

	/**
	 * Update an object.
	 *
	 * @param  int    $object_id
	 *
	 * @param  array  $args
	 *
	 * @return mixed
	 */
	public static function update( $object_id, $args = [] ) {
		return static::get_data_store()::update( $object_id, $args );
	}

	/**
	 * Delete an item.
	 *
	 * @param  int   $object_id
	 * @param  bool  $force_delete
	 *
	 * @return bool
	 */
	public static function delete( $object_id, $force_delete = false ): bool {
		return static::get_data_store()::delete( $object_id, $force_delete );
	}

	/**
	 * Convert all metadata to array.
	 *
	 * @return array
	 */
	public function to_array(): array {

		if ( ! static::$data_store || ! static::$data_store::$metadata ) {
			return [];
		}

		$data = [];

		foreach ( static::$data_store::$metadata as $meta ) {
			$data[ $meta ] = $this->{"get_$data"};
		}

		return $data;

	}

}
