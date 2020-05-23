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
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

use BernskioldMedia\WP\PluginScaffold\Exceptions\Data_Store_Exception;
use BernskioldMedia\WP\PluginScaffold\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Class Taxonomy
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Taxonomy extends Data_Store_WP {

	/**
	 * Post type classes which this taxonomy is assigned to.
	 *
	 * @var array
	 */
	protected static $post_types = [];

	/**
	 * Default permissions for the taxonomy.
	 *
	 * @var \bool[][]
	 */
	protected static $default_permissions = [
		'administrator' => [
			'manage' => true,
			'assign' => true,
			'delete' => true,
			'edit'   => true,
		],
		'editor'        => [
			'manage' => true,
			'assign' => true,
			'delete' => true,
			'edit'   => true,
		],
		'author'        => [
			'manage' => true,
			'assign' => true,
			'delete' => false,
			'edit'   => true,
		],
		'contributor'   => [
			'manage' => false,
			'assign' => true,
			'delete' => false,
			'edit'   => false,
		],
		'subscriber'    => [
			'manage' => false,
			'assign' => false,
			'delete' => false,
			'edit'   => false,
		],
	];

	/**
	 * Handle the registration logic here to
	 * set up and register the object with WordPress.
	 *
	 * @return void
	 */
	abstract public static function register();

	/**
	 * Create a new term in the taxonomy.
	 *
	 * @param  string $name
	 * @param  array  $args
	 *
	 * @return int
	 * @throws Data_Store_Exception
	 */
	public static function create( $name, $args = [] ): int {

		if ( ! isset( $data['name'] ) ) {
			throw new Data_Store_Exception(
				'Tried to create a term, but the term name was not passed correctly.',
				[
					'taxonomy' => static::get_key(),
					'name'     => $name,
					'data'     => $args,
				]
			);
		}

		$existing_term_id = static::does_object_exist( $name );

		if ( $existing_term_id ) {
			return static::update( $existing_term_id, $args );
		}

		/**
		 * Attempt to create the term.
		 */
		$response = wp_insert_term( $name, static::get_key(), $args );

		/**
		 * Handle the error in term creation.
		 */
		if ( is_wp_error( $response ) ) {
			throw new Data_Store_Exception(
				'Could not create a new term.',
				[
					'name'    => $data['name'],
					'message' => $response->get_error_message(),
				]
			);
		}

		Log::info(
			'Successfully created a new term.',
			[
				'name'    => $data['name'],
				'term_id' => $response['term_id'],
			]
		);

		return (int) $response['term_id'];

	}

	/**
	 * Update a term.
	 *
	 * @param  int   $term_id
	 * @param  array $args
	 *
	 * @return int
	 * @throws Data_Store_Exception
	 */
	public static function update( $term_id, $args = [] ): int {

		/**
		 * Update the term with the data.
		 */
		$updated = wp_update_term( $term_id, static::get_key(), $args );

		/**
		 * Handle the output for fail and success
		 * to make it reliable and consistent.
		 */
		if ( is_wp_error( $updated ) ) {
			throw new Data_Store_Exception(
				'Could not update term.',
				[
					'taxonomy' => static::get_key(),
					'name'     => $args['name'],
					'term_id'  => $term_id,
					'message'  => $updated->get_error_message(),
				]
			);
		}

		Log::info(
			'Successfully updated the term.',
			[
				'taxonomy' => static::get_key(),
				'term_id'  => $term_id,
				'name'     => $args['name'],
			]
		);

		return (int) $updated['term_id'];

	}

	/**
	 * Delete Term
	 *
	 * @param  int  $term_id
	 * @param  bool $force_delete
	 *
	 * @return bool
	 * @throws Data_Store_Exception
	 */
	public static function delete( $term_id, $force_delete = false ): bool {

		/**
		 * For error handling...
		 *
		 * @see https://developer.wordpress.org/reference/functions/wp_delete_term/
		 */
		$response = wp_delete_term( $term_id, static::get_key() );

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
			throw new Data_Store_Exception(
				'Tried to delete a term, but the term does not exist.',
				[
					'term_id'  => $term_id,
					'taxonomy' => static::get_key(),
				]
			);
		}

		/**
		 * A 0 response is that we get if we tried to remove
		 * the default category in WordPress.
		 */
		if ( 0 === $response ) {
			throw new Data_Store_Exception(
				'Tried to delete the default category. You must not do this.',
				[
					'term_id'  => $term_id,
					'taxonomy' => static::get_key(),
				]
			);
		}

		/**
		 * Finally, we get a WP error if the taxonomy that was passed
		 * does not exist.
		 */
		if ( is_wp_error( $response ) ) {
			throw new Data_Store_Exception(
				'Tried to delete a term, but the taxonomy did not exist.',
				[
					'taxonomy' => static::get_key(),
					'term_id'  => $term_id,
				]
			);
		}

		/**
		 * This should have handled both all successes
		 * and all defined errors. But if we still get here,
		 * we throw a false as a fallback.
		 */
		return false;

	}

	/**
	 * Check if the term exists.
	 *
	 * @param  string|int $term
	 *
	 * @return int|mixed
	 */
	public static function does_object_exist( $term ): ?int {
		$term_exists = term_exists( $term, static::get_key() );

		if ( is_array( $term_exists ) ) {
			return $term_exists['term_id'];
		}

		return $term_exists;
	}

	/**
	 * Get the post types this taxonomy is assigned to.
	 *
	 * @return Custom_Post_Type[]
	 */
	public static function get_post_types(): array {
		return static::$post_types;
	}

	/**
	 * Get the Post Type Keys that this taxonomy is assigned to.
	 *
	 * @return array
	 */
	public static function get_post_type_keys(): array {
		$output = [];

		foreach ( static::get_post_types() as $class ) {
			$output[] = $class::get_key();
		}

		return $output;
	}

	/**
	 * Get the default capabilities.
	 *
	 * @return string[]
	 */
	protected static function get_capabilities(): array {

		$capabilities = [];

		foreach ( static::$default_permissions['administrator'] as $permission => $is_granted ) {
			$capabilities[ $permission . '_terms' ] = self::add_key_to_capability( $permission );
		}

		return $capabilities;

	}

}
