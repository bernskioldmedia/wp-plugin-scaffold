<?php
/**
 * Abstract Taxonomy Class
 *
 * The abstract taxonomy class helps us create a predicable
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
 * Class Taxonomy
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Taxonomy implements Data_Store_Interface {

	/**
	 * Taxonomy Key
	 *
	 * @var
	 */
	protected $key;

	/**
	 * Post types which this taxonomy is assigned to.
	 *
	 * @var array
	 */
	protected $post_types = [];

	/**
	 * Taxonomy constructor.
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
	 * Create a new term in the taxonomy.
	 *
	 * @param array $data
	 *
	 * @return bool|int
	 */
	public function create( $data ) {

		if ( ! isset( $data['name'] ) ) {
			Log::error( 'Tried to create a term, but the term name was not passed correctly.', [
				'taxonomy' => $this->get_key(),
				'data'     => $data,
			] );

			return false;
		}

		$term_exists = term_exists( $data['name'], $this->get_key() );

		/**
		 * If the term exists already, we don't
		 * create it, but return its ID.
		 */
		if ( $term_exists ) {

			/**
			 * Sometimes WordPress returns an array, if it does
			 * we handle it here to always return the term ID.
			 */
			if ( is_array( $term_exists ) ) {
				$existing_term_id = $term_exists['term_id'];
			} else {
				$existing_term_id = $term_exists;
			}

			Log::info( 'Could not create term, it already exists.', [
				'name'     => $data['name'],
				'term_id'  => $existing_term_id,
				'taxonomy' => $this->get_key(),
			] );

			return $existing_term_id;
		}

		/**
		 * Attempt to create the term.
		 */
		$response = wp_insert_term( $data['name'], $this->get_key() );

		/**
		 * Handle the error in term creation.
		 */
		if ( is_wp_error( $response ) ) {

			Log::error( 'Could not create a new term.', [
				'name'    => $data['name'],
				'message' => $response->get_error_message(),
			] );

			return false;

		} else {

			Log::info( 'Successfully created a new term.', [
				'name'    => $data['name'],
				'term_id' => $response['term_id'],
			] );

			return (int) $response['term_id'];

		}

	}

	/**
	 * Get a term.
	 *
	 * @param int $term_id
	 *
	 * @return null|\WP_Term
	 */
	public function read( $term_id ) {

		$term = get_term_by( 'id', $term_id, $this->get_key() );

		/**
		 * Ensure that we can rely on expected output.
		 */
		if ( $term instanceof \WP_Term ) {
			return $term;
		} else {
			Log::error( 'Tried to get a term that does not exist.', [
				'term_id'  => $term_id,
				'taxonomy' => $this->get_key(),
			] );

			return null;

		}

	}

	/**
	 * Update a term.
	 *
	 * @param int   $term_id
	 * @param array $data
	 *
	 * @return bool
	 * @todo This seems to be failing.
	 */
	public function update( $term_id, $data ) {

		/**
		 * Update the term with the data.
		 */
		$updated = wp_update_term( $term_id, $this->get_key(), $data );

		/**
		 * Handle the output for fail and success
		 * to make it reliable and consistent.
		 */
		if ( is_wp_error( $updated ) ) {

			Log::error( 'Could not update term.', [
				'taxonomy' => $this->get_key(),
				'name'     => $data['name'],
				'term_id'  => $term_id,
				'message'  => $updated->get_error_message(),
			] );

			return false;
		} else {
			Log::info( 'Successfully updated the term.', [
				'taxonomy' => $this->get_key(),
				'term_id'  => $term_id,
				'name'     => $data['name'],
			] );

			return true;
		}

	}

	/**
	 * Delete Term
	 *
	 * @param int $term_id
	 *
	 * @return bool
	 *
	 */
	public function delete( $term_id ) {

		/**
		 * For error handling...
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_delete_term/
		 */
		$response = wp_delete_term( $term_id, $this->get_key() );

		/**
		 * A true response means everything was fine.
		 */
		if ( true === $response ) {
			return true;
		}

		/**
		 * A false response means the term does
		 * not exist.
		 */
		if ( false === $response ) {

			Log::error( 'Tried to delete a term, but the term does not exist.', [
				'term_id'  => $term_id,
				'taxonomy' => $this->get_key(),
			] );

			return false;
		}

		/**
		 * A 0 response is that we get if we tried to remove
		 * the default category in WordPress.
		 */
		if ( 0 === $response ) {
			Log::error( 'Tried to delete the default category. You must not do this.', [
				'term_id'  => $term_id,
				'taxonomy' => $this->get_key(),
			] );

			return false;
		}

		/**
		 * Finally, we get a WP error if the taxonomy that was passed
		 * does not exist.
		 */
		if ( is_wp_error( $response ) ) {
			Log::error( 'Tried to delete a term, but the taxonomy did not exist.', [
				'taxonomy' => $this->get_key(),
				'term_id'  => $term_id,
			] );
		}

		/**
		 * This should have handled both all successes
		 * and all defined errors. But if we still get here,
		 * we throw a false as a fallback.
		 */
		return false;

	}

	/**
	 * Get Taxonomy Key
	 *
	 * @return string
	 */
	public function get_key() {
		return (string) $this->key;
	}

	/**
	 * Get singular key.
	 *
	 * We use this for permissions checks, and for taxonomies,
	 * there are not singular permissions. Therefore,
	 * we return the plural key here.
	 *
	 * @return string
	 */
	public function get_singular_key() {
		return (string) $this->key;
	}

	/**
	 * Get the post types this taxonomy is assigned to.
	 *
	 * @return array
	 */
	public function get_post_types() {
		return (array) $this->post_types;
	}

}
