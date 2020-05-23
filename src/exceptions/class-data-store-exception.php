<?php

namespace BernskioldMedia\WP\PluginScaffold\Exceptions;

use BernskioldMedia\WP\PluginScaffold\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Class Data_Store_Exception
 *
 * @package BernskioldMedia\WP\PluginScaffold\Exceptions
 */
class Data_Store_Exception extends \Exception {

	/**
	 * @var array
	 */
	private $data;

	/**
	 * Construct the exception.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 *
	 * @param  string $message  [optional] The Exception message to throw.
	 * @param  array  $data
	 */
	public function __construct( string $message, array $data ) {
		parent::__construct( $message );
		$this->data = $data;
		Log::error( $message, $data );
	}

	/**
	 * Get Data Store
	 *
	 * @return array
	 */
	public function get_data(): array {
		return $this->data;
	}

}
