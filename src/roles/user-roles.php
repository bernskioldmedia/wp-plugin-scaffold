<?php

namespace BernskioldMedia\WP\PluginScaffold\Roles;

use BernskioldMedia\WP\PluginScaffold\Abstracts\Custom_Post_Type;
use BernskioldMedia\WP\PluginScaffold\Abstracts\Taxonomy;
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
		self::give_admins_access_to_data_stores();
	}

	/**
	 * By default, we want admins to have access to any data
	 * store that we add in this plugin.
	 */
	protected static function give_admins_access_to_data_stores(): void {

		$capabilities = [];
		$role         = get_role( 'administrator' );

		if ( ! $role ) {
			return;
		}

		foreach ( Plugin::$data_stores as $data_store_class ) {

			$reflection = new \ReflectionClass( $data_store_class );

			if ( $reflection->isSubclassOf( Custom_Post_Type::class ) ) {
				$capabilities[] = 'edit_' . $data_store_class::get_key();
				$capabilities[] = 'read_' . $data_store_class::get_key();
				$capabilities[] = 'delete_' . $data_store_class::get_key();

				$capabilities[] = 'edit_' . $data_store_class::get_plural_key();
				$capabilities[] = 'edit_others_' . $data_store_class::get_plural_key();
				$capabilities[] = 'publish_' . $data_store_class::get_plural_key();
				$capabilities[] = 'read_private_' . $data_store_class::get_plural_key();
			}

			if ( $reflection->isSubclassOf( Taxonomy::class ) ) {
				$capabilities[] = 'manage_' . $data_store_class::get_plural_key();
				$capabilities[] = 'delete_' . $data_store_class::get_plural_key();
				$capabilities[] = 'assign_' . $data_store_class::get_plural_key();
			}

		}

		foreach ( $capabilities as $capability ) {
			$role->add_cap( $capability );
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
