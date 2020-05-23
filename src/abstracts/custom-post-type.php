<?php
/**
 * Abstract Custom Post Type Class
 *
 * The abstract custom post type class helps us create a predicable
 * set of features to use when accessing taxonomy data stores.
 *
 * It extends the Data Store interface which ensures predicable
 * CRUD functions available for all types of data, from CPTs
 * to taxonomies or even custom tables.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

use BernskioldMedia\WP\PluginScaffold\Exceptions\Data_Store_Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Class Custom_Post_Type
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Custom_Post_Type extends Data_Store_WP {

	/**
	 * Custom_Post_Type constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register' ], 10 );
	}

	/**
	 * Handle the registration logic here to
	 * set up and register the object with WordPress.
	 *
	 * @return void
	 */
	abstract public function register(): void;

	/**
	 * Create Custom Post Type Object
	 *
	 * @param  string  $name
	 * @param  array   $args
	 *
	 * @return int
	 * @throws Data_Store_Exception
	 */
	public function create( $name, $args ): int {

		/**
		 * Check that the required data for creation is set.
		 */
		if ( ! $name ) {
			throw new Data_Store_Exception( 'Tried to create an object, but the object name was not passed in correctly.', $args );
		}

		/**
		 * Set up the post data.
		 */
		$post_data = wp_parse_args( $args, [
			'post_type'    => $this->get_key(),
			'post_content' => '',
			'post_status'  => 'publish',
		] );

		/**
		 * Create the post!
		 */
		$response = wp_insert_post( $post_data, true );

		/**
		 * Bail now if we couldn't create.
		 */
		if ( is_wp_error( $response ) ) {
			throw new Data_Store_Exception( 'Tried to create an object, but it failed.', [
				'error'     => $response->get_error_message(),
				'post_data' => $post_data,
			] );

		}

		Log::info( 'Successfully created a new object.', [
			'object_id' => $response,
			'post_data' => $post_data,
		] );

		return (int) $response;

	}

	/**
	 * Updates a post.
	 *
	 * @param  int    $object_id
	 * @param  array  $args
	 *
	 * @return int
	 * @throws Data_Store_Exception
	 */
	public function update( $object_id, $args ): int {

		$data = wp_parse_args( $args, [
			'ID'        => $object_id,
			'post_type' => $this->get_key(),
		] );

		$response = wp_update_post( $data, true );

		/**
		 * Bail now if we couldn't create.
		 */
		if ( is_wp_error( $response ) ) {
			throw new Data_Store_Exception( 'Tried to update an object, but it failed.', [
				'error'     => $response->get_error_message(),
				'post_data' => $data,
			] );
		}

		Log::info( 'Successfully updated an object.', [
			'object_id' => $response,
			'post_data' => $data,
		] );

		return (int) $response;

	}

	/**
	 * Delete an object.
	 *
	 * @param  int   $object_id
	 * @param  bool  $skip_trash
	 *
	 * @return bool
	 * @throws Data_Store_Exception
	 */
	public function delete( $object_id, $skip_trash = false ): bool {
		$response = wp_delete_post( $object_id, $skip_trash );

		if ( false === $response ) {
			throw new Data_Store_Exception( 'Tried to delete object, but it failed.', [
				'object_id'  => $object_id,
				'skip_trash' => $skip_trash,
			] );
		}

		Log::info( 'An object was successfully deleted.', [
			'object_id'  => $object_id,
			'skip_trash' => $skip_trash,
		] );

		return true;
	}


	/**
	 * Check if posts exists. Returns integer if exists, or null.
	 *
	 * @param  string  $post_title
	 *
	 * @return null|int
	 */
	public function does_object_exist( $post_title ): ?int {

		$post = get_page_by_title( $post_title, OBJECT, $this->get_key() );

		if ( null !== $post ) {
			return (int) $post->ID;
		}

		return null;

	}

	/**
	 * Get and store terms from a taxonomy.
	 *
	 * @param  Data|integer  $object    Data object or object ID.
	 * @param  string        $taxonomy  Taxonomy name e.g. product_cat.
	 *
	 * @return array of terms
	 */
	protected static function get_term_ids( $object, $taxonomy ): array {

		if ( is_numeric( $object ) ) {
			$object_id = $object;
		} else {
			$object_id = $object->get_id();
		}

		$terms = get_the_terms( $object_id, $taxonomy );

		if ( false === $terms || is_wp_error( $terms ) ) {
			return [];
		}

		return wp_list_pluck( $terms, 'term_id' );
	}

}
