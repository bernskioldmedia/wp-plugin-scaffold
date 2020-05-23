<?php
/**
 * Abstract Data
 *
 * The abstract data functions sets extends our
 * data interface, which ensures we always have a base
 * set of functions ready.
 *
 * These functions are available whenever we create classes
 * that interface with data, be it Custom Post Types, full
 * custom database tables or taxonomies.
 *
 * We create these "helper classes" to make accessing
 * data very predicable and easy.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class Data
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
abstract class Taxonomy_Data extends Data {

	/**
	 * Get Property
	 *
	 * @param  string  $field_key
	 *
	 * @return mixed|null
	 */
	protected function get_prop( $field_key ) {
		return get_field( $field_key, $this->get_id_for_acf() );
	}

	/**
	 * Set property
	 *
	 * @param  string  $field_key
	 * @param  mixed   $new_value
	 *
	 * @return bool|int|mixed
	 */
	protected function set_prop( $field_key, $new_value ) {
		return update_field( $field_key, $new_value, $this->get_id_for_acf() );
	}

	/**
	 * Get the ID for use in get_field by ACF.
	 *
	 * @return string
	 */
	protected function get_id_for_acf(): string {
		return 'term_' . $this->get_id();
	}

}
