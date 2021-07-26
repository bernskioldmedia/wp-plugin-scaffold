<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces\Hookable;
\defined('ABSPATH') || exit;
abstract class AssetManager implements Hookable
{
    /**
     * Public scripts to register.
     *
     * Takes either a simple name => subfolder array or an array
     * with more parameters:
     * name => [
     *      'dependencies' => [],
     *      'version' => string,
     *      'in_footer' => true/false,
     *      'subfolder' => string,
     * ]
     *
     * The subfolder is relative to the plugin root, so could be
     * for example: assets/scripts/dist
     *
     * If you use the short registration with only name => subfolder, we
     * expect wp-scripts to have been used to build, generating a name.asset.php
     * file as well.
     *
     * The asset name will be used for the filename.
     */
    protected static array $public_scripts = [];
    /**
     * Admin scripts to register.
     * For usage, see docs for static::$public_scripts.
     */
    protected static array $admin_scripts = [];
    /**
     * Public styles to register.
     *
     * Takes either a simple name => subfolder array or an array
     * with more parameters:
     * name => [
     *      'dependencies' => [],
     *      'version' => string,
     *      'media' => print|media|css,
     *      'subfolder' => string,
     * ]
     *
     * The subfolder is relative to the plugin root, so could be
     * for example: assets/styles/dist
     *
     * If you use the short registration with only name => subfolder, we
     * expect wp-scripts to have been used to build, generating a name.asset.php
     * file as well.
     *
     * The asset name will be used for the filename.
     */
    protected static array $public_styles = [];
    /**
     * Admin styles to register.
     * For usage, see docs for static::$admin_styles.
     */
    protected static array $admin_styles = [];
    /**
     * Define the action priority for the asset registration.
     */
    protected static int $register_priority = 10;
    /**
     * Defines the action priority for the asset enqueueing.
     */
    protected static int $enqueue_priority = 100;
    /**
     * Hook in functions for auto-registering the styles
     * from the arrays, as well as auto-loading of
     * the four enqueue functions if present.
     */
    public static function hooks() : void
    {
        /**
         * Register
         */
        if (!empty(static::$public_scripts)) {
            add_action('wp_enqueue_scripts', [self::class, 'register_public_scripts'], static::$register_priority);
        }
        if (!empty(static::$admin_scripts)) {
            add_action('admin_enqueue_scripts', [self::class, 'register_admin_scripts'], static::$register_priority);
        }
        if (!empty(static::$public_styles)) {
            add_action('wp_enqueue_scripts', [self::class, 'register_public_styles'], static::$register_priority);
        }
        if (!empty(static::$admin_styles)) {
            add_action('admin_enqueue_scripts', [self::class, 'register_admin_styles'], static::$register_priority);
        }
        /**
         * Enqueue
         */
        if (\method_exists(static::class, 'enqueue_public_scripts')) {
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_public_scripts'], static::$enqueue_priority);
        }
        if (\method_exists(static::class, 'enqueue_admin_scripts')) {
            add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_scripts'], static::$enqueue_priority);
        }
        if (\method_exists(static::class, 'enqueue_public_styles')) {
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_public_styles'], static::$enqueue_priority);
        }
        if (\method_exists(static::class, 'enqueue_admin_styles')) {
            add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_styles'], static::$enqueue_priority);
        }
    }
    /**
     * Registers the scripts given in $public_scripts.
     */
    public static function register_public_scripts() : void
    {
        static::register_scripts(static::$public_scripts);
    }
    /**
     * Registers the scripts given in $admin_scripts.
     */
    public static function register_admin_scripts() : void
    {
        static::register_scripts(static::$admin_scripts);
    }
    /**
     * Registers the styles given in $public_styles.
     */
    public static function register_public_styles() : void
    {
        static::register_styles(static::$public_styles);
    }
    /**
     * Registers the styles given in $admin_styles.
     */
    public static function register_admin_styles() : void
    {
        static::register_styles(static::$admin_styles);
    }
    /**
     * Handle the registration of scripts based on our own custom array.
     */
    protected static function register_scripts(array $scripts) : void
    {
        foreach ($scripts as $name => $data) {
            if (\is_array($data)) {
                $version = $data['version'] ?? BasePlugin::get_version();
                $dependencies = $data['dependencies'] ?? [];
                $subfolder = $data['subfolder'] ?? '';
                $in_footer = $data['in_footer'] ?? \true;
            } else {
                $subfolder = $data;
                $in_footer = \true;
                $meta = (require_once BasePlugin::get_path($subfolder . '/' . $name . '.asset.php'));
                $dependencies = $meta['dependencies'] ?? [];
                $version = $meta['version'] ?? [];
            }
            wp_register_script($name, BasePlugin::get_url($subfolder . '/' . $name . '.js'), $dependencies, $version, $in_footer);
        }
    }
    /**
     * Handle the registration of styles based on our custom array.
     */
    protected static function register_styles(array $styles) : void
    {
        foreach ($styles as $name => $data) {
            if (\is_array($data)) {
                $version = $data['version'] ?? BasePlugin::get_version();
                $dependencies = $data['dependencies'] ?? [];
                $subfolder = $data['subfolder'] ?? '';
                $media = $data['media'] ?? 'all';
            } else {
                $subfolder = $data;
                $media = 'all';
                $meta = (require_once BasePlugin::get_path($subfolder . '/' . $name . '.asset.php'));
                $dependencies = $meta['dependencies'] ?? [];
                $version = $meta['version'] ?? [];
            }
            wp_register_style($name, BasePlugin::get_url($subfolder . '/' . $name . '.js'), $dependencies, $version, $media);
        }
    }
}
