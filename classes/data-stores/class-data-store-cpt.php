<?php
/**
 * Custom Post Type
 */

namespace BernskioldMedia\Client\PluginName;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Data_Store_CPT
 *
 * @package BernskioldMedia\Client\PluginName
 */
class Data_Store_CPT extends Custom_Post_Type {

	/**
	 * Post Type Key
	 *
	 * @var string
	 */
	protected $key = 'cpts';

	/**
	 * Singular Key
	 *
	 * @var string
	 */
	protected $singular_key = 'cpt';

	/**
	 * Data_Store_CPT constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Register Post Type
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
	 */
	public function register() {

		$labels = [
			'name'                  => _x( 'Examples', 'Post Type General Name', 'PLUGINTEXTDOMAINHERE' ),
			'singular_name'         => _x( 'Example', 'Post Type Singular Name', 'PLUGINTEXTDOMAINHERE' ),
			'menu_name'             => __( 'Examples', 'PLUGINTEXTDOMAINHERE' ),
			'name_admin_bar'        => __( 'Examples', 'PLUGINTEXTDOMAINHERE' ),
			'archives'              => __( 'Example Archives', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item_colon'     => __( 'Parent Example:', 'PLUGINTEXTDOMAINHERE' ),
			'all_items'             => __( 'All Examples', 'PLUGINTEXTDOMAINHERE' ),
			'add_new_item'          => __( 'Add New Example', 'PLUGINTEXTDOMAINHERE' ),
			'add_new'               => __( 'Add New', 'PLUGINTEXTDOMAINHERE' ),
			'new_item'              => __( 'New Example', 'PLUGINTEXTDOMAINHERE' ),
			'edit_item'             => __( 'Edit Example', 'PLUGINTEXTDOMAINHERE' ),
			'update_item'           => __( 'Update Example', 'PLUGINTEXTDOMAINHERE' ),
			'view_item'             => __( 'View Example', 'PLUGINTEXTDOMAINHERE' ),
			'search_items'          => __( 'Search Example', 'PLUGINTEXTDOMAINHERE' ),
			'not_found'             => __( 'Not found', 'PLUGINTEXTDOMAINHERE' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'PLUGINTEXTDOMAINHERE' ),
			'featured_image'        => __( 'Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'set_featured_image'    => __( 'Set Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'remove_featured_image' => __( 'Remove Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'use_featured_image'    => __( 'Use as Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'insert_into_item'      => __( 'Insert into example', 'PLUGINTEXTDOMAINHERE' ),
			'uploaded_to_this_item' => __( 'Uploaded to this example', 'PLUGINTEXTDOMAINHERE' ),
			'items_list'            => __( 'Examples list', 'PLUGINTEXTDOMAINHERE' ),
			'items_list_navigation' => __( 'Examples list navigation', 'PLUGINTEXTDOMAINHERE' ),
			'filter_items_list'     => __( 'Filter examples list', 'PLUGINTEXTDOMAINHERE' ),
			'attributes'            => __( 'Attributes', 'PLUGINTEXTDOMAINHERE' ),
		];

		$supports = [
			'title',
			'editor',
			'revisions',
		];

		$args = [
			'label'               => __( 'Examples', 'PLUGINTEXTDOMAINHERE' ),
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
			'has_archive'         => 'example',
			'rewrite'             => [
				'slug'       => 'example',
				'with_front' => true,
			],
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'delete_with_user'    => null,
			'show_in_rest'        => true,
			'capability_type'     => [ $this->get_singular_key(), $this->get_key() ],
		];

		register_post_type( $this->get_key(), $args );

	}

}
