<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase;

\defined('ABSPATH') || exit;
/**
 * Class BasePlugin
 *
 * @package BernskioldMedia\WP\PluginBase
 */
class BasePlugin
{
    /**
     * A machine readable plugin slug, used to automatically prefix certain actions.
     */
    protected static string $slug = 'wp_plugin_base';
    /**
     * Version
     */
    protected static string $version = '1.0.0';
    /**
     * Database Version
     */
    protected static string $database_version = '1000';
    /**
     * Plugin Textdomain
     */
    protected static string $textdomain = 'wp-plugin-base';
    /**
     * Main plugin file path.
     */
    protected static string $plugin_file_path = '';
    /**
     * Plugin Class Instance Variable
     *
     * @var static
     */
    protected static $_instance = null;
    /**
     * Add a list of Facet classes here that will be
     * loaded alongside this plugin.
     */
    protected static array $facets = [];
    /**
     * The data stores (class names) that will be loaded
     * alongside this plugin.
     */
    protected static array $data_stores = [];
    /**
     * The REST endpoints (class names) that will be loaded
     * alongside this plugin.
     */
    protected static array $rest_endpoints = [];
    /**
     * Include a list of customizer section classes to
     * load them with the theme.
     */
    protected static array $customizer_sections = [];
    /**
     * Include a list of classes to boot when the plugin runs.
     * These will all fire on the init_hooks method.
     */
    protected static array $boot = [];
    /**
     * Plugin Instantiator
     *
     * @return static
     */
    public static function instance()
    {
        if (\is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }
    /**
     * Cloning is forbidden.
     *
     * @since 1.2
     */
    private function __clone()
    {
    }
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.2
     */
    private function __wakeup()
    {
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        if (\method_exists(static::class, 'has_dependencies')) {
            if (static::has_dependencies()) {
                $this->init_hooks();
                if (\method_exists($this, 'load_blocks')) {
                    $this->load_blocks();
                }
                do_action(static::$slug . '_loaded');
            }
        } else {
            $this->init_hooks();
            if (\method_exists($this, 'load_blocks')) {
                $this->load_blocks();
            }
            do_action(static::$slug . '_loaded');
        }
    }
    /**
     * Hooks that are run on the time of init.
     */
    protected function init_hooks() : void
    {
        do_action(static::$slug . '_init_hooks');
        add_action('init', [static::class, 'load_languages']);
        if (\method_exists($this, 'register_blocks')) {
            add_action('init', [$this, 'register_blocks']);
        }
        if (\method_exists(static::class, 'setup_admin_columns_storage_repository')) {
            add_action('acp/storage/repositories', [static::class, 'setup_admin_columns_storage_repository'], 10, 2);
        }
        if (!empty(static::$boot)) {
            foreach (static::$boot as $bootableClass) {
                $bootableClass::hooks();
            }
        }
        if (!empty(static::$data_stores)) {
            foreach (static::$data_stores as $data_store) {
                new $data_store();
            }
        }
        if (!empty(static::$rest_endpoints)) {
            foreach (static::$rest_endpoints as $endpoint) {
                (new $endpoint())->load();
            }
        }
        if (!empty(static::$rest_endpoints)) {
            foreach (static::$rest_endpoints as $endpoint) {
                (new $endpoint())->load();
            }
        }
        if (!empty(static::$facets)) {
            foreach (static::$facets as $facet) {
                add_filter('facetwp_facets', [$facet, 'make']);
            }
        }
        if (!empty(static::$customizer_sections)) {
            foreach (static::$customizer_sections as $class) {
                new $class();
            }
        }
    }
    /**
     * Load plugin translations.
     */
    public static function load_languages() : void
    {
        $locale = is_admin() && \function_exists('WPPS_Vendor\\get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, static::get_textdomain());
        unload_textdomain(static::get_textdomain());
        // Start checking in the main language dir.
        load_textdomain(static::get_textdomain(), WP_LANG_DIR . '/' . static::get_textdomain() . '/' . static::get_textdomain() . '-' . $locale . '.mo');
        // Otherwise, load from the plugin.
        load_plugin_textdomain(static::get_textdomain(), \false, \dirname(plugin_basename(static::$plugin_file_path)) . '/languages');
    }
    /**
     * Get the path to the plugin folder, or the specified
     * file relative to the plugin folder home.
     */
    public static function get_path(string $file = '') : string
    {
        return untrailingslashit(plugin_dir_path(static::$plugin_file_path)) . '/' . $file;
    }
    /**
     * Get the URL to the plugin folder, or the specified
     * file relative to the plugin folder home.
     */
    public static function get_url(string $file = '') : string
    {
        return untrailingslashit(plugin_dir_url(static::$plugin_file_path)) . '/' . $file;
    }
    /**
     * Get the URL to the assets folder, or the specified
     * file relative to the assets folder home.
     */
    public static function get_assets_url(string $file = '') : string
    {
        return static::get_url('assets/' . $file);
    }
    /**
     * Get AJAX URL
     */
    public static function get_ajax_url() : string
    {
        return admin_url('admin-ajax.php', 'relative');
    }
    /**
     * Get the Plugin's Version
     */
    public static function get_version() : string
    {
        return static::$version;
    }
    /**
     * Get the database version number.
     */
    public static function get_database_version() : string
    {
        return static::$database_version;
    }
    /**
     * Get the plugin textdomain.
     */
    public static function get_textdomain() : string
    {
        return static::$textdomain;
    }
}
