<?php
/**
 * Abstract Queries Functions
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Abstract_Queries
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Queries implements Queries_Interface {

	/**
	 * Post Type Key
	 *
	 * @var string
	 */
	public static $post_type;

	/**
	 * Default records per page.
	 *
	 * @var int
	 */
	public static $records_per_page = 25;

	/**
	 * Get all records.
	 *
	 * @return int[]
	 */
	public static function get_all() {

		$args = [
			'post_type'      => static::get_post_type(),
			'posts_per_page' => static::get_records_per_page(),
			'fields'         => 'ids',
		];

		$query = new \WP_Query( $args );

		return $query->get_posts();

	}

	/**
	 * Get default records per page.
	 *
	 * Includes filter with post type parameter, to modify
	 * the output selectively if necessary.
	 *
	 * @return int
	 */
	public static function get_records_per_page() {
		return (int) apply_filters( 'ilpb_records_per_page', static::$records_per_page, static::get_post_type() );
	}

	/**
	 * Get Post Type
	 *
	 * @return string
	 */
	public static function get_post_type() {
		return (string) static::$post_type;
	}

}
