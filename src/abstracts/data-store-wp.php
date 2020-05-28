<?php

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

use BernskioldMedia\WP\PluginScaffold\Interfaces\Data_Store_Interface;

defined( 'ABSPATH' ) || exit;

/**
 * Class Data_Store_WP
 *
 * @package BernskioldMedia\WP\Event\Abstracts
 */
abstract class Data_Store_WP implements Data_Store_Interface {

	/**
	 * Object Type Key
	 *
	 * @var string
	 */
	protected static $key;

	/**
	 * Object Type Plural Key
	 *
	 * @var string
	 */
	protected static $plural_key;

	/**
	 * These permissions override the default set.
	 *
	 * @var array
	 */
	protected static $permissions = [];

	/**
	 * A list of default permissions for core roles.
	 *
	 * @var array
	 */
	protected static $default_permissions = [
		'administrator' => [],
	];

	/**
	 * Array of metadata (field) keys.
	 *
	 * @var array
	 */
	public static $metadata = [];

	/**
	 * Class name of the data class.
	 *
	 * @var string
	 */
	protected static $data_class;

	/**
	 * Custom_Post_Type constructor.
	 */
	public function __construct() {
		add_action( 'init', [ static::class, 'register' ], 10 );
		add_action( 'bm_event_install', [ static::class, 'register' ] );

		if ( method_exists( static::class, 'fields' ) ) {
			add_action( 'acf/init', [ static::class, 'fields' ] );
		}

		if ( method_exists( static::class, 'admin_columns' ) ) {
			add_action( 'ac/ready', [ static::class, 'admin_columns' ] );
		}

		if ( static::$metadata ) {
			add_action( 'rest_api_init', [ static::class, 'rest_fields' ] );
		}

		if ( method_exists( static::class, 'query_modifications' ) ) {
			add_filter( 'pre_get_posts', [ static::class, 'query_modifications' ], 10, 1 );
		}

	}

	/**
	 * Create an item.
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int
	 */
	abstract public static function create( $name, $args = [] ): int;

	/**
	 * Update an item with new values.
	 *
	 * @param  int           $object_id
	 * @param  array|string  $args
	 *
	 * @return int
	 */
	abstract public static function update( $object_id, $args = [] ): int;

	/**
	 * Delete an item.
	 *
	 * @param  int   $object_id
	 * @param  bool  $force_delete
	 *
	 * @return bool
	 */
	abstract public static function delete( $object_id, $force_delete = false ): bool;

	/**
	 * Get Object Type Key
	 *
	 * @return string
	 */
	public static function get_key(): string {
		return static::$key;
	}

	/**
	 * Get Object Type Plural Key
	 *
	 * @return string
	 */
	public static function get_plural_key(): string {
		return static::$plural_key;
	}

	/**
	 * Adds key to the capability.
	 *
	 * @param  string  $capability
	 * @param  bool    $plural
	 *
	 * @return string
	 */
	protected static function add_key_to_capability( $capability, $plural = true ): string {

		if ( ! $plural ) {
			return $capability . '_' . static::get_key();
		}

		return $capability . '_' . static::get_plural_key();
	}

	/**
	 * Add permissions to roles.
	 *
	 * @param  array  $permissions
	 */
	protected static function add_permissions_to_roles( array $permissions ): void {

		foreach ( $permissions as $role => $capabilities ) {
			$role = get_role( $role );

			if ( ! $role ) {
				continue;
			}

			foreach ( $capabilities as $capability => $is_granted ) {
				$role->add_cap( static::add_key_to_capability( $capability ), $is_granted );
			}
		}

	}

	/**
	 * Setup capabilities based on the defined permissions.
	 *
	 * @return void
	 */
	public static function setup_permissions(): void {
		$permissions = wp_parse_args( static::$permissions, static::$default_permissions );
		static::add_permissions_to_roles( $permissions );
	}

	/**
	 * Helper wrapper to register a rest field based on the info
	 * we already have in the data store.
	 *
	 * @param  string         $key
	 * @param  callable|null  $get_callback
	 * @param  callable|null  $update_callback
	 */
	protected static function register_rest_field( string $key, ?callable $get_callback, ?callable $update_callback = null ) {
		register_rest_field( static::get_key(), $key, [
			'get_callback'    => $get_callback,
			'update_callback' => $update_callback,
		] );
	}

	/**
	 * Register rest API fields based on the metadata
	 * defined in the static::$metadata array.
	 *
	 * Magically uses the defined data class to look up
	 * setters and getters based on the metadata name.
	 */
	public static function rest_fields(): void {

		if ( ! static::$metadata || ! static::$data_class ) {
			return;
		}

		foreach ( static::$metadata as $meta ) {
			self::register_rest_field( $meta, function ( $object ) use ( $meta ) {

				/**
				 * Get Callback
				 */

				if ( ! $object['id'] ) {
					return null;
				}

				$data = new static::$data_class( $object['id'] );

				if ( ! method_exists( $data, "get_$meta" ) ) {
					return null;
				}

				return $data->{"get_$meta"}();

			}, function ( $value, $object ) use ( $meta ) {

				/**
				 * Update Callback
				 */

				if ( $object['id'] ) {
					$data = new static::$data_class( $object['id'] );

					if ( method_exists( $data, "set_$meta" ) ) {
						return $data->{"set_$meta"}();
					}
				}
			} );
		}

	}

}
