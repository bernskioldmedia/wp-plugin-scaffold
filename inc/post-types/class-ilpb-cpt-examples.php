<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Custom Post Type: Items
 */
class ILPB_CPT_Examples {

	public function __construct() {

		// Initialize the Post Type
		add_action( 'init', array( $this, 'post_type' ), 1 );

	}

	public function post_type() {

		$labels = array(
			'name'                  => _x( 'Items', 'Post Type General Name', 'PLUGINTEXTDOMAINHERE' ),
			'singular_name'         => _x( 'Item', 'Post Type Singular Name', 'PLUGINTEXTDOMAINHERE' ),
			'menu_name'             => __( 'Items', 'PLUGINTEXTDOMAINHERE' ),
			'name_admin_bar'        => __( 'Items', 'PLUGINTEXTDOMAINHERE' ),
			'archives'              => __( 'Item Archives', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item_colon'     => __( 'Parent Item:', 'PLUGINTEXTDOMAINHERE' ),
			'all_items'             => __( 'All Items', 'PLUGINTEXTDOMAINHERE' ),
			'add_new_item'          => __( 'Add New Item', 'PLUGINTEXTDOMAINHERE' ),
			'add_new'               => __( 'Add New', 'PLUGINTEXTDOMAINHERE' ),
			'new_item'              => __( 'New Item', 'PLUGINTEXTDOMAINHERE' ),
			'edit_item'             => __( 'Edit Item', 'PLUGINTEXTDOMAINHERE' ),
			'update_item'           => __( 'Update Item', 'PLUGINTEXTDOMAINHERE' ),
			'view_item'             => __( 'View Item', 'PLUGINTEXTDOMAINHERE' ),
			'search_items'          => __( 'Search Item', 'PLUGINTEXTDOMAINHERE' ),
			'not_found'             => __( 'Not found', 'PLUGINTEXTDOMAINHERE' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'PLUGINTEXTDOMAINHERE' ),
			'featured_image'        => __( 'Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'set_featured_image'    => __( 'Set featured image', 'PLUGINTEXTDOMAINHERE' ),
			'remove_featured_image' => __( 'Remove featured image', 'PLUGINTEXTDOMAINHERE' ),
			'use_featured_image'    => __( 'Use as featured image', 'PLUGINTEXTDOMAINHERE' ),
			'insert_into_item'      => __( 'Insert into item', 'PLUGINTEXTDOMAINHERE' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'PLUGINTEXTDOMAINHERE' ),
			'items_list'            => __( 'Items list', 'PLUGINTEXTDOMAINHERE' ),
			'items_list_navigation' => __( 'Items list navigation', 'PLUGINTEXTDOMAINHERE' ),
			'filter_items_list'     => __( 'Filter items list', 'PLUGINTEXTDOMAINHERE' ),
		);

		$rewrite = array(
			'slug'                  => _x( 'items', 'items slug', 'PLUGINTEXTDOMAINHERE' ),
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);

		$args = array(
			'label'                 => __( 'Items', 'PLUGINTEXTDOMAINHERE' ),
			'description'           => __( 'Holds items for the website.', 'PLUGINTEXTDOMAINHERE' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-format-quote',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => _x( 'items', 'item slug', 'PLUGINTEXTDOMAINHERE' ),
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);

		register_post_type( 'ilpb_examples', $args );

	}

}