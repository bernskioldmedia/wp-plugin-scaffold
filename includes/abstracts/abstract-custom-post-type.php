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
 * @package WP_Plugin_Scaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Custom_Post_Type
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Custom_Post_Type implements Data_Store_Interface {

	/**
	 * Custom Post Type Key
	 *
	 * @var
	 */
	protected $key;

	/*
	 * Custom Post Type Singular Key
	 */
	protected $singular_key;

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
	abstract public function register();

	/**
	 * Create Custom Post Type Object
	 *
	 * @param array $args
	 *
	 * @return bool|int
	 */
	public function create( $args ) {

		/**
		 * Check that the required data for creation is set.
		 */
		if ( ! isset( $args['post_title'] ) ) {
			Log::error( 'Tried to create an object, but the object name was not passed in correctly.', $args );

			return false;
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
			Log::error( 'Tried to create an object, but it failed.', [
				'error'     => $response->get_error_message(),
				'post_data' => $post_data,
			] );

			return false;
		}

		Log::info( 'Successfully created a new object.', [
			'object_id' => $response,
			'post_data' => $post_data,
		] );

		return (int) $response;

	}

	/**
	 * Retrieve an object from the database.
	 *
	 * @param int $object_id
	 *
	 * @return array|\WP_Post|null
	 */
	public function read( $object_id ) {
		return get_post( $object_id );
	}

	/**
	 * Updates a post.
	 *
	 * @param int   $object_id
	 * @param array $args
	 *
	 * @return bool|int
	 */
	public function update( $object_id, $args ) {

		$data = wp_parse_args( $args, [
			'ID'        => $object_id,
			'post_type' => $this->get_key(),
		] );

		$response = wp_update_post( $data, true );

		/**
		 * Bail now if we couldn't create.
		 */
		if ( is_wp_error( $response ) ) {
			Log::error( 'Tried to update an object, but it failed.', [
				'error'     => $response->get_error_message(),
				'post_data' => $data,
			] );

			return false;
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
	 * @param int  $object_id
	 * @param bool $skip_trash
	 *
	 * @return bool
	 */
	public function delete( $object_id, $skip_trash = false ) {
		$response = wp_delete_post( $object_id, $skip_trash );

		if ( false === $response ) {
			Log::error( 'Tried to delete object, but it failed.', [
				'object_id'  => $object_id,
				'skip_trash' => $skip_trash,
			] );

			return false;
		}

		Log::info( 'An object was successfully deleted.', [
			'object_id'  => $object_id,
			'skip_trash' => $skip_trash,
		] );

		return true;
	}

	/**
	 * Get Custom_Post_Type Key
	 *
	 * @return string
	 */
	public function get_key() {
		return (string) $this->key;
	}

	/**
	 * Get singular Key
	 *
	 * @return string
	 */
	public function get_singular_key() {
		return (string) $this->singular_key;
	}

	/**
	 * Check if posts exists.
	 *
	 * @param string $post_title
	 *
	 * @return bool|int
	 */
	public function does_post_exist( $post_title ) {

		$post = get_page_by_title( $post_title, OBJECT, $this->get_key() );

		if ( null !== $post ) {
			return (int) $post->ID;
		} else {
			return 0;
		}

	}

}
