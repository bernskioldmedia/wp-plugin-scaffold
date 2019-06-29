<?php
/**
 * Taxonomy
 */

namespace BernskioldMedia\Client\PluginName;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Data_Store_Taxonomy
 *
 * @package BernskioldMedia\Client\PluginName
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
			'name'                       => _x( 'Types', 'Taxonomy General Name', 'PLUGINTEXTDOMAINHERE' ),
			'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'PLUGINTEXTDOMAINHERE' ),
			'menu_name'                  => __( 'Types', 'PLUGINTEXTDOMAINHERE' ),
			'all_items'                  => __( 'All Types', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item'                => __( 'Parent Type', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item_colon'          => __( 'Parent Type:', 'PLUGINTEXTDOMAINHERE' ),
			'new_item_name'              => __( 'New Type Name', 'PLUGINTEXTDOMAINHERE' ),
			'add_new_item'               => __( 'Add New Type', 'PLUGINTEXTDOMAINHERE' ),
			'edit_item'                  => __( 'Edit Type', 'PLUGINTEXTDOMAINHERE' ),
			'update_item'                => __( 'Update Type', 'PLUGINTEXTDOMAINHERE' ),
			'view_item'                  => __( 'View Type', 'PLUGINTEXTDOMAINHERE' ),
			'separate_items_with_commas' => __( 'Separate types with commas', 'PLUGINTEXTDOMAINHERE' ),
			'add_or_remove_items'        => __( 'Add or remove types', 'PLUGINTEXTDOMAINHERE' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'PLUGINTEXTDOMAINHERE' ),
			'popular_items'              => __( 'Popular Types', 'PLUGINTEXTDOMAINHERE' ),
			'search_items'               => __( 'Search Types', 'PLUGINTEXTDOMAINHERE' ),
			'not_found'                  => __( 'Not Found', 'PLUGINTEXTDOMAINHERE' ),
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
