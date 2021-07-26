<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\DataStores;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces\DataStoreInterface;
\defined('ABSPATH') || exit;
/**
 * Class Data_Store_WP
 */
abstract class DataStoreWP implements DataStoreInterface
{
    /**
     * Object Type Key
     */
    protected static string $key;
    /**
     * Object Type Plural Key
     */
    protected static string $plural_key;
    /**
     * These permissions override the default set.
     */
    protected static array $permissions = [];
    /**
     * A list of default permissions for core roles.
     */
    protected static array $default_permissions = ['administrator' => []];
    /**
     * Array of metadata (field) keys.
     */
    public static array $metadata = [];
    /**
     * Class name of the data class.
     */
    protected static string $data_class;
    /**
     * When set to true the admin columns in Admin Columns Pro
     * will be stored in a directory in this plugin as opposed to in the database.
     */
    public static bool $store_admin_columns = \false;
    /**
     * Add the field group class names here to automatically
     * load them alongside this data store.
     */
    protected static array $field_groups = [];
    /**
     * Custom_Post_Type constructor.
     */
    public function __construct()
    {
        add_action('init', [static::class, 'register'], 10);
        if (\method_exists(static::class, 'fields')) {
            add_action('init', [static::class, 'fields'], 20);
        }
        if (static::$metadata) {
            add_action('rest_api_init', [static::class, 'rest_fields']);
        }
        if (\method_exists(static::class, 'query_modifications')) {
            add_filter('pre_get_posts', [static::class, 'query_modifications']);
        }
        // Load field groups if they exist.
        if (!empty(static::$field_groups)) {
            foreach (static::$field_groups as $field_group) {
                add_action('init', [$field_group, 'make'], 30);
            }
        }
    }
    /**
     * Create an item.
     */
    public static abstract function create(string $name, array $args = []) : int;
    /**
     * Update an item with new values.
     */
    public static abstract function update(int $object_id, array $args = []) : int;
    /**
     * Delete an item.
     */
    public static abstract function delete(int $object_id, bool $force_delete = \false) : bool;
    /**
     * Get Object Type Key
     */
    public static function get_key() : string
    {
        return static::$key;
    }
    /**
     * Get Object Type Plural Key
     */
    public static function get_plural_key() : string
    {
        return static::$plural_key;
    }
    /**
     * Adds key to the capability.
     */
    protected static function add_key_to_capability(string $capability, bool $plural = \true) : string
    {
        if (!$plural) {
            return $capability . '_' . static::get_key();
        }
        return $capability . '_' . static::get_plural_key();
    }
    /**
     * Add permissions to roles.
     */
    protected static function add_permissions_to_roles(array $permissions) : void
    {
        foreach ($permissions as $role => $capabilities) {
            $role = get_role($role);
            if (!$role) {
                continue;
            }
            foreach ($capabilities as $capability => $is_granted) {
                $role->add_cap(static::add_key_to_capability($capability), $is_granted);
            }
        }
    }
    /**
     * Setup capabilities based on the defined permissions.
     */
    public static function setup_permissions() : void
    {
        $permissions = wp_parse_args(static::$permissions, static::$default_permissions);
        static::add_permissions_to_roles($permissions);
    }
    /**
     * Helper wrapper to register a rest field based on the info
     * we already have in the data store.
     */
    protected static function register_rest_field(string $key, ?callable $get_callback, ?callable $update_callback = null)
    {
        register_rest_field(static::get_key(), $key, ['get_callback' => $get_callback, 'update_callback' => $update_callback]);
    }
    /**
     * Register rest API fields based on the metadata
     * defined in the static::$metadata array.
     *
     * Magically uses the defined data class to look up
     * setters and getters based on the metadata name.
     */
    public static function rest_fields() : void
    {
        if (!static::$metadata || !static::$data_class) {
            return;
        }
        foreach (static::$metadata as $meta) {
            static::register_rest_field($meta, function ($object) use($meta) {
                /**
                 * Get Callback
                 */
                if (!$object['id']) {
                    return null;
                }
                $data = new static::$data_class($object['id']);
                if (!\method_exists($data, "get_{$meta}")) {
                    return null;
                }
                return $data->{"get_{$meta}"}();
            }, function ($value, $object) use($meta) {
                /**
                 * Update Callback
                 */
                if ($object['id']) {
                    $data = new static::$data_class($object['id']);
                    if (!\method_exists($data, "set_{$meta}")) {
                        return null;
                    }
                    return $data->{"set_{$meta}"}();
                }
            });
        }
    }
}
