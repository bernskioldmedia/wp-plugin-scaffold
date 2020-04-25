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
	 * Create an item.
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int
	 */
	abstract public function create( $name, $args ): int;

	/**
	 * Update an item with new values.
	 *
	 * @param  int           $object_id
	 * @param  array|string  $args
	 *
	 * @return int
	 */
	abstract public function update( $object_id, $args ): int;

	/**
	 * Delete an item.
	 *
	 * @param  int   $object_id
	 * @param  bool  $force_delete
	 *
	 * @return bool
	 */
	abstract public function delete( $object_id, $force_delete = false ): bool;

	/**
	 * Get Object Type Key
	 *
	 * @return string
	 */
	public function get_key(): string {
		return static::$key;
	}

	/**
	 * Get Object Type Plural Key
	 *
	 * @return string
	 */
	public function get_plural_key(): string {
		return static::$plural_key;
	}

}
