<?php
/**
 * Data Store for Custom Post Type
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Data_Stores;

use BernskioldMedia\WP\PluginScaffold\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Class CPT
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class CPT extends Abstracts\Custom_Post_Type {

	/**
	 * Post Type Key
	 *
	 * @var string
	 */
	protected static $key = 'cpt';

	/**
	 * Plural Key
	 *
	 * @var string
	 */
	protected static $plural_key = 'cpts';

	/**
	 * Register Post Type
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
	 */
	public static function register(): void {

		$labels = [
			'name'                  => _x( 'Examples', 'Post Type General Name', 'wp-plugin-scaffold' ),
			'singular_name'         => _x( 'Example', 'Post Type Singular Name', 'wp-plugin-scaffold' ),
			'menu_name'             => __( 'Examples', 'wp-plugin-scaffold' ),
			'name_admin_bar'        => __( 'Examples', 'wp-plugin-scaffold' ),
			'archives'              => __( 'Example Archives', 'wp-plugin-scaffold' ),
			'parent_item_colon'     => __( 'Parent Example:', 'wp-plugin-scaffold' ),
			'all_items'             => __( 'All Examples', 'wp-plugin-scaffold' ),
			'add_new_item'          => __( 'Add New Example', 'wp-plugin-scaffold' ),
			'add_new'               => __( 'Add New', 'wp-plugin-scaffold' ),
			'new_item'              => __( 'New Example', 'wp-plugin-scaffold' ),
			'edit_item'             => __( 'Edit Example', 'wp-plugin-scaffold' ),
			'update_item'           => __( 'Update Example', 'wp-plugin-scaffold' ),
			'view_item'             => __( 'View Example', 'wp-plugin-scaffold' ),
			'search_items'          => __( 'Search Example', 'wp-plugin-scaffold' ),
			'not_found'             => __( 'Not found', 'wp-plugin-scaffold' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'wp-plugin-scaffold' ),
			'featured_image'        => __( 'Featured Image', 'wp-plugin-scaffold' ),
			'set_featured_image'    => __( 'Set Featured Image', 'wp-plugin-scaffold' ),
			'remove_featured_image' => __( 'Remove Featured Image', 'wp-plugin-scaffold' ),
			'use_featured_image'    => __( 'Use as Featured Image', 'wp-plugin-scaffold' ),
			'insert_into_item'      => __( 'Insert into example', 'wp-plugin-scaffold' ),
			'uploaded_to_this_item' => __( 'Uploaded to this example', 'wp-plugin-scaffold' ),
			'items_list'            => __( 'Examples list', 'wp-plugin-scaffold' ),
			'items_list_navigation' => __( 'Examples list navigation', 'wp-plugin-scaffold' ),
			'filter_items_list'     => __( 'Filter examples list', 'wp-plugin-scaffold' ),
			'attributes'            => __( 'Attributes', 'wp-plugin-scaffold' ),
		];

		$supports = [
			'title',
			'editor',
			'revisions',
		];

		$args = [
			'label'               => __( 'Examples', 'wp-plugin-scaffold' ),
			'labels'              => $labels,
			'supports'            => $supports,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-users',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => _x( 'example', 'examples post type archive slug', 'wp-plugin-scaffold' ),
			'rewrite'             => [
				'slug'       => _x( 'example', 'examples post type single slug', 'wp-plugin-scaffold' ),
				'with_front' => true,
			],
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'delete_with_user'    => null,
			'show_in_rest'        => true,
			'capability_type'     => [ self::get_key(), self::get_plural_key() ],
		];

		register_post_type( self::get_key(), $args );

	}

}
