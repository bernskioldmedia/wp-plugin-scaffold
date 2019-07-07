<?php
/**
 * Class MainFile
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

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

		$version = WP_Plugin_Scaffold::get_plugin_version();

		$this->assertInternalType( 'string', $version );

	}

}
