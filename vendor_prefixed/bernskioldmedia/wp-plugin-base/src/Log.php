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
 * @package BernskioldMedia\WP\PluginBase
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase;

use WPPS_Vendor\Monolog\Handler\StreamHandler;
use WPPS_Vendor\Monolog\Logger;
\defined('ABSPATH') || exit;
/**
 * Class Log
 *
 * @package BernskioldMedia\WP\PluginBase
 */
abstract class Log
{
    /**
     * Class Instance
     *
     * @var $instance
     */
    protected static $instance;
    protected static string $log_name;
    protected static string $log_path;
    /**
     * Method to return the Monolog instance
     */
    public static function get() : Logger
    {
        if (!static::$instance) {
            static::configure();
        }
        return static::$instance;
    }
    /**
     * Configure Monolog to use a rotating files system.
     */
    protected static function configure() : void
    {
        // Create the logger.
        $logger = new Logger(static::$log_name);
        // Define the log level depending on environment.
        if (\defined('LOGGING_LEVEL')) {
            $log_level = \LOGGING_LEVEL;
        } elseif (\defined('ENABLE_LOGGING') && \true === \ENABLE_LOGGING) {
            $log_level = Logger::DEBUG;
        } else {
            $log_level = Logger::ERROR;
        }
        // Set up the local saving.
        $logger->pushHandler(new StreamHandler(static::$log_path, $log_level));
        static::$instance = $logger;
    }
    public static function debug(string $message, array $context = []) : void
    {
        static::get()->addDebug($message, $context);
    }
    public static function info(string $message, array $context = []) : void
    {
        static::get()->addInfo($message, $context);
    }
    public static function notice(string $message, array $context = []) : void
    {
        static::get()->addNotice($message, $context);
    }
    public static function warning(string $message, array $context = []) : void
    {
        static::get()->addWarning($message, $context);
    }
    public static function error(string $message, array $context = []) : void
    {
        static::get()->addError($message, $context);
    }
    public static function critical(string $message, array $context = []) : void
    {
        static::get()->addCritical($message, $context);
    }
    public static function alert(string $message, array $context = []) : void
    {
        static::get()->addAlert($message, $context);
    }
    public static function emergency(string $message, array $context = []) : void
    {
        static::get()->addEmergency($message, $context);
    }
}
