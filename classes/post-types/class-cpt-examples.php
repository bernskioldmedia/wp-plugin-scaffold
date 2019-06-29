<?php
/**
 * Custom Post Type: Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CPT_Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */
class CPT_Examples {

	/**
	 * Post Type Key
	 *
	 * @var string
	 */
	protected $key = 'cpt_examples';

	/**
	 * Class CPT_Examples Constructor
	 */
	public function __construct() {

		// Initialize the Post Type.
		add_action( 'init', array( $this, 'post_type' ), 1 );

	}

	/**
	 * Register Post Type
	 *
	 * @see https://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function post_type() {

		$labels = array(
			'name'                  => _x( 'ITEMS', 'Post Type General Name', 'PLUGINTEXTDOMAINHERE' ),
			'singular_name'         => _x( 'ITEM', 'Post Type Singular Name', 'PLUGINTEXTDOMAINHERE' ),
			'menu_name'             => __( 'ITEMS', 'PLUGINTEXTDOMAINHERE' ),
			'name_admin_bar'        => __( 'ITEMS', 'PLUGINTEXTDOMAINHERE' ),
			'archives'              => __( 'ITEM Archives', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item_colon'     => __( 'Parent ITEM:', 'PLUGINTEXTDOMAINHERE' ),
			'all_items'             => __( 'All ITEMS', 'PLUGINTEXTDOMAINHERE' ),
			'add_new_item'          => __( 'Add New ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'add_new'               => __( 'Add New', 'PLUGINTEXTDOMAINHERE' ),
			'new_item'              => __( 'New ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'edit_item'             => __( 'Edit ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'update_item'           => __( 'Update ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'view_item'             => __( 'View ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'search_items'          => __( 'Search ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'not_found'             => __( 'Not found', 'PLUGINTEXTDOMAINHERE' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'PLUGINTEXTDOMAINHERE' ),
			'featured_image'        => __( 'Featured Image', 'PLUGINTEXTDOMAINHERE' ),
			'set_featured_image'    => __( 'Set featured image', 'PLUGINTEXTDOMAINHERE' ),
			'remove_featured_image' => __( 'Remove featured image', 'PLUGINTEXTDOMAINHERE' ),
			'use_featured_image'    => __( 'Use as featured image', 'PLUGINTEXTDOMAINHERE' ),
			'insert_into_item'      => __( 'Insert into ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'uploaded_to_this_item' => __( 'Uploaded to this ITEM', 'PLUGINTEXTDOMAINHERE' ),
			'items_list'            => __( 'ITEMS list', 'PLUGINTEXTDOMAINHERE' ),
			'items_list_navigation' => __( 'ITEMS list navigation', 'PLUGINTEXTDOMAINHERE' ),
			'filter_items_list'     => __( 'Filter ITEMS list', 'PLUGINTEXTDOMAINHERE' ),
			'attributes'            => __( 'Attributes', 'PLUGINTEXTDOMAINHERE' ),
		);

		$rewrite = array(
			'slug'       => _x( 'items', 'items slug', 'PLUGINTEXTDOMAINHERE' ),
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$supports = array(
			'title',
			'editor',
			'revisions',
		);

		$args = array(
			'label'               => __( 'Items', 'PLUGINTEXTDOMAINHERE' ),
			'description'         => __( 'Holds items for the website.', 'PLUGINTEXTDOMAINHERE' ),
			'labels'              => $labels,
			'supports'            => $supports,
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-format-quote',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => _x( 'items', 'item slug', 'PLUGINTEXTDOMAINHERE' ),
			'rewrite'             => $rewrite,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'delete_with_user'    => null,
			'show_in_rest'        => false,
			'capability_type'     => 'page',
		);

		register_post_type( $this->get_key(), $args );

	}

	/**
	 * Get Post Type Key
	 *
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

}
