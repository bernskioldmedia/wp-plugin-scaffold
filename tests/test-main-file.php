<?php
/**
 * Class MainFile
 *
 * @package BernskioldMedia\Client\PluginName
 */

namespace BernskioldMedia\Client\PluginName;

/**
 * Main File Tests
 */
class MainFile extends \WP_UnitTestCase {

	/**
	 * Test: Plugin Version
	 *
	 * Make sure plugin version returns an integer.
	 */
	function test_get_plugin_version() {

		$version = Ilmenite_PB::get_plugin_version();

		$this->assertInternalType( 'string', $version );

	}

}
