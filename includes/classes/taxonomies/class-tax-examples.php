<?php
/**
 * Custom Taxonomy: Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Tax_Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */
class Tax_Examples {

	/**
	 * Taxonomy Key
	 *
	 * @var string
	 */
	protected $key = 'ilpb_examples';

	/**
	 * Class Tax_Examples Constructor
	 */
	public function __construct() {

		// Initialize the taxonomy.
		add_action( 'init', array( $this, 'taxonomy' ), 1 );

	}

	/**
	 * Register Taxonomy
	 */
	public function taxonomy() {

		$labels = array(
			'name'                       => _x( 'Examples', 'Taxonomy General Name', 'PLUGINTEXTDOMAINHERE' ),
			'singular_name'              => _x( 'Example', 'Taxonomy Singular Name', 'PLUGINTEXTDOMAINHERE' ),
			'menu_name'                  => __( 'Examples', 'PLUGINTEXTDOMAINHERE' ),
			'all_items'                  => __( 'All Examples', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item'                => __( 'Parent Example', 'PLUGINTEXTDOMAINHERE' ),
			'parent_item_colon'          => __( 'Parent Example:', 'PLUGINTEXTDOMAINHERE' ),
			'new_item_name'              => __( 'New Example Name', 'PLUGINTEXTDOMAINHERE' ),
			'add_new_item'               => __( 'Add New Example', 'PLUGINTEXTDOMAINHERE' ),
			'edit_item'                  => __( 'Edit Example', 'PLUGINTEXTDOMAINHERE' ),
			'update_item'                => __( 'Update Example', 'PLUGINTEXTDOMAINHERE' ),
			'view_item'                  => __( 'View Example', 'PLUGINTEXTDOMAINHERE' ),
			'separate_items_with_commas' => __( 'Separate examples with commas', 'PLUGINTEXTDOMAINHERE' ),
			'add_or_remove_items'        => __( 'Add or remove examples', 'PLUGINTEXTDOMAINHERE' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'PLUGINTEXTDOMAINHERE' ),
			'popular_items'              => __( 'Popular Examples', 'PLUGINTEXTDOMAINHERE' ),
			'search_items'               => __( 'Search Examples', 'PLUGINTEXTDOMAINHERE' ),
			'not_found'                  => __( 'Not Found', 'PLUGINTEXTDOMAINHERE' ),
		);

		$rewrite = array(
			'slug'         => _x( 'examples', 'taxonomy rewrite slug', 'PLUGINTEXTDOMAINHERE' ),
			'with_front'   => true,
			'hierarchical' => true,
		);

		$args = array(
			'labels'             => $labels,
			'description'        => _x( 'An example taxonomy.', 'taxonomy description', 'PLUGINTEXTDOMAINHERE' ),
			'hierarchical'       => true,
			'public'             => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_menu'       => true,
			'show_in_quick_edit' => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
			'rewrite'            => $rewrite,
			'show_in_rest'       => false,
		);

		// Which post types should this taxonomy be
		// registered for?
		$apply_to_post_types = array(
			'post',
		);

		register_taxonomy( $this->get_key(), $apply_to_post_types, $args );

	}

	/**
	 * Get Taxonomy Key
	 *
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

}
