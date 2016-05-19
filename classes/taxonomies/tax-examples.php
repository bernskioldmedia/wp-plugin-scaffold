<?php
/**
 * Custom Taxonomy: Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Class Tax_Examples
 *
 * @package BernskioldMedia\Client\PluginName
 */
class Tax_Examples {

	/**
	 * Class Tax_Examples Constructor
	 */
	public function __construct() {

		// Initialize the taxonomy
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
				'parent_item'                => __( 'Parent Examples', 'PLUGINTEXTDOMAINHERE' ),
				'parent_item_colon'          => __( 'Parent Examples:', 'PLUGINTEXTDOMAINHERE' ),
				'new_item_name'              => __( 'New Examples Name', 'PLUGINTEXTDOMAINHERE' ),
				'add_new_item'               => __( 'Add New Examples', 'PLUGINTEXTDOMAINHERE' ),
				'edit_item'                  => __( 'Edit Examples', 'PLUGINTEXTDOMAINHERE' ),
				'update_item'                => __( 'Update Examples', 'PLUGINTEXTDOMAINHERE' ),
				'view_item'                  => __( 'View Examples', 'PLUGINTEXTDOMAINHERE' ),
				'separate_items_with_commas' => __( 'Separate examples with commas', 'PLUGINTEXTDOMAINHERE' ),
				'add_or_remove_items'        => __( 'Add or remove examples', 'PLUGINTEXTDOMAINHERE' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'PLUGINTEXTDOMAINHERE' ),
				'popular_items'              => __( 'Popular Examples', 'PLUGINTEXTDOMAINHERE' ),
				'search_items'               => __( 'Search Examples', 'PLUGINTEXTDOMAINHERE' ),
				'not_found'                  => __( 'Not Found', 'PLUGINTEXTDOMAINHERE' ),
			);

			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => false,
				'show_tagcloud'              => false,
				'rewrite'                    => false,
			);

			// Which post types should this taxonomy be
			// registered for?
			$apply_to_post_types = array(
				'post',
			);

			register_taxonomy( 'ilpb_examples', $apply_to_post_types, $args );

	}

}