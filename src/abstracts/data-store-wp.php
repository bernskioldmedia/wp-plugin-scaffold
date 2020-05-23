<?php

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

use BernskioldMedia\WP\PluginScaffold\Interfaces\Data_Store_Interface;

/**
 * Class Data_Store_WP
 *
 * @package BernskioldMedia\WP\PluginScaffold\Abstracts
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
	 * Custom_Post_Type constructor.
	 */
	public function __construct() {
		add_action( 'init', [ static::class, 'register' ], 10 );
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
	 * Setup capabilities based on the defined permissions.
	 *
	 * @return void
	 */
	abstract public static function setup_permissions(): void;

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
				$role->add_cap( $capability, $is_granted );
			}
		}

	}

}
