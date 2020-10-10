<?php

namespace BernskioldMedia\WP\PluginScaffold\Roles;

use BernskioldMedia\WP\PluginScaffold\Plugin;

defined( 'ABSPATH' ) || exit;

class User_Roles {

	/**
	 * Define unused roles with keys here,
	 * we will then remove them.
	 *
	 * @var array
	 */
	public static $unused_roles = [];

	/**
	 * Boot the user roles add.
	 */
	public static function install(): void {
		self::remove_unused_roles();

		foreach ( Plugin::$data_stores as $data_store_class ) {
			$data_store_class::setup_permissions();
		}

	}

	/**
	 * Remove any unused roles.
	 */
	protected static function remove_unused_roles(): void {
		$unused_roles = apply_filters( 'wp_plugin_scaffold_unused_roles', self::$unused_roles );

		foreach ( $unused_roles as $role ) {
			remove_role( $role );
		}
	}

}
