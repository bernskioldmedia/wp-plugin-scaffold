<?php
/**
 * Logger
 *
 * Adds a logger based on Monolog easily accessible as static functions
 * in the plugin.
 *
 * See link below for an overview of the log levels.
 *
 * @link    https://github.com/Seldaek/monolog/blob/HEAD/doc/01-usage.md#log-levels
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */

namespace BernskioldMedia\WP\PluginScaffold;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Log
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Log {

	/**
	 * Class Instance
	 *
	 * @var $instance
	 */
	protected static $instance;

	/**
	 * Method to return the Monolog instance
	 *
	 * @return \Monolog\Logger
	 */
	public static function get() {
		if ( ! self::$instance ) {
			self::configure();
		}

		return self::$instance;
	}

	/**
	 * Configure Monolog to use a rotating files system.
	 */
	protected static function configure() {

		// Set the log path outside of WordPress public!
		$log_path = ABSPATH . '../logs/wp-plugin-scaffold.log';

		// Create the logger.
		$logger = new Logger( 'wp-plugin-scaffold' );

		// Define the log level depending on environment.

		if ( defined( 'LOGGING_LEVEL' ) ) {
			$log_level = LOGGING_LEVEL;
		} elseif ( defined( 'ENABLE_LOGGING' ) && true === ENABLE_LOGGING ) {
			$log_level = Logger::DEBUG;
		} else {
			$log_level = Logger::ERROR;
		}

		// Set up the local saving.
		$logger->pushHandler( new StreamHandler( $log_path, $log_level ) );

		self::$instance = $logger;
	}

	/**
	 * Debug
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function debug( $message, array $context = [] ) {
		self::get()->addDebug( $message, $context );
	}

	/**
	 * Info
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function info( $message, array $context = [] ) {
		self::get()->addInfo( $message, $context );
	}

	/**
	 * Notice
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function notice( $message, array $context = [] ) {
		self::get()->addNotice( $message, $context );
	}

	/**
	 * Warning
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function warning( $message, array $context = [] ) {
		self::get()->addWarning( $message, $context );
	}

	/**
	 * Error
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function error( $message, array $context = [] ) {
		self::get()->addError( $message, $context );
	}

	/**
	 * Critical
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function critical( $message, array $context = [] ) {
		self::get()->addCritical( $message, $context );
	}

	/**
	 * Alert
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function alert( $message, array $context = [] ) {
		self::get()->addAlert( $message, $context );
	}

	/**
	 * Emergency
	 *
	 * @param string $message Message.
	 * @param array  $context Data.
	 */
	public static function emergency( $message, array $context = [] ) {
		self::get()->addEmergency( $message, $context );
	}

}
