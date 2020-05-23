<?php
/**
 * CPT Data
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 */

namespace BernskioldMedia\WP\PluginScaffold\Data;

use BernskioldMedia\WP\PluginScaffold\Abstracts\Data;

defined( 'ABSPATH' ) || exit;

/**
 * Class CPT
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class CPT extends Data {

	/**
	 * Get Customer Name
	 *
	 * @return string
	 */
	public function get_name() {
		return get_the_title( $this->get_id() );
	}

}
