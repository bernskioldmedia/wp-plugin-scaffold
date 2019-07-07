<?php
/**
 * Taxonomy
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Data_Store_Taxonomy
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Data_Store_Taxonomy extends Taxonomy {

	/**
	 * Taxonomy Key
	 *
	 * @var string
	 */
	protected $key = 'taxonomykey';

	/**
	 * Taxonomy Post Types
	 *
	 * @var string
	 */
	protected $post_types = [
		'example',
	];

	/**
	 * Data_Store_Taxonomy constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Register the taxonomy.
	 */
	public function register() {

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
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_menu'       => true,
			'show_in_quick_edit' => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'rewrite'            => [
				'slug' => 'taxonomy',
			],
			'show_in_rest'       => true,
			'capabilities'       => [
				'manage_terms' => 'manage_' . $this->get_key(),
				'edit_terms'   => 'manage_' . $this->get_key(),
				'delete_terms' => 'delete_' . $this->get_key(),
				'assign_terms' => 'assign_' . $this->get_key(),
			],
		];

		register_taxonomy( $this->get_key(), $this->get_post_types(), $args );

	}

}
