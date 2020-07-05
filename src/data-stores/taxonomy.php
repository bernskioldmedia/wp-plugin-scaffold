<?php
/**
 * Taxonomy
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Data_Stores;

use BernskioldMedia\WP\PluginBase\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Class Taxonomy
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Taxonomy extends Abstracts\Taxonomy {

	/**
	 * Taxonomy Key
	 *
	 * @var string
	 */
	protected static $key = 'type';

	/**
	 * Taxonomy Plural Key
	 *
	 * @var string
	 */
	protected static $plural_key = 'types';

	/**
	 * Taxonomy Post Types
	 *
	 * @var string
	 */
	protected static $post_types = [
		CPT::class,
	];

	/**
	 * Register the taxonomy.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
	 */
	public static function register() {

		$labels = [
			'name'                       => _x( 'Types', 'Taxonomy General Name', 'wp-plugin-scaffold' ),
			'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'wp-plugin-scaffold' ),
			'menu_name'                  => __( 'Types', 'wp-plugin-scaffold' ),
			'all_items'                  => __( 'All Types', 'wp-plugin-scaffold' ),
			'parent_item'                => __( 'Parent Type', 'wp-plugin-scaffold' ),
			'parent_item_colon'          => __( 'Parent Type:', 'wp-plugin-scaffold' ),
			'new_item_name'              => __( 'New Type Name', 'wp-plugin-scaffold' ),
			'add_new_item'               => __( 'Add New Type', 'wp-plugin-scaffold' ),
			'edit_item'                  => __( 'Edit Type', 'wp-plugin-scaffold' ),
			'update_item'                => __( 'Update Type', 'wp-plugin-scaffold' ),
			'view_item'                  => __( 'View Type', 'wp-plugin-scaffold' ),
			'separate_items_with_commas' => __( 'Separate types with commas', 'wp-plugin-scaffold' ),
			'add_or_remove_items'        => __( 'Add or remove types', 'wp-plugin-scaffold' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wp-plugin-scaffold' ),
			'popular_items'              => __( 'Popular Types', 'wp-plugin-scaffold' ),
			'search_items'               => __( 'Search Types', 'wp-plugin-scaffold' ),
			'not_found'                  => __( 'Not Found', 'wp-plugin-scaffold' ),
		];

		$args = [
			'labels'             => $labels,
			'hierarchical'       => true,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_in_rest'       => true, // Must be true for Gutenberg.
			'show_tagcloud'      => false,
			'rewrite'            => [
				'slug'         => _x( 'types', 'types taxonomy slug ', 'wp-plugin-scaffold' ),
				'with_front'   => false,
				'hierarchical' => true,
			],
			'capabilities'       => self::get_capabilities(),
		];

		register_taxonomy( self::get_key(), self::get_post_type_keys(), $args );

	}

}
