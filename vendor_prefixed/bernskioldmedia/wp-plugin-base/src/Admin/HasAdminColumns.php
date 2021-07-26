<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Admin;

\defined('ABSPATH') || exit;
/**
 * Trait Has_Admin_Columns
 *
 * Add this trait to the BasePlugin inherited class to
 * enable support for saving admin columns. In each post type you
 * may then enable the support for saving admin columns.
 *
 * @package BernskioldMedia\WP\PluginBase\Traits
 */
trait HasAdminColumns
{
    public static function setup_admin_columns_storage_repository(array $repositories, $factory) : array
    {
        // Ensure we also have data stores.
        if (!\property_exists(static::class, 'data_stores')) {
            return $repositories;
        }
        foreach (static::$data_stores as $data_store) {
            // Keep going if this data does doesn't allow storing.
            if (!$data_store::$store_admin_columns) {
                continue;
            }
            $rules = new \AC\ListScreenRepository\Rules(\AC\ListScreenRepository\Rules::MATCH_ANY);
            $rules->add_rule(new \AC\ListScreenRepository\Rule\EqualType($data_store::get_key()));
            $repositories['data_store_' . $data_store::get_key()] = $factory->create(static::get_path('admin-columns/' . $data_store::get_key()), static::is_admin_columns_writeable(), $rules);
        }
        return $repositories;
    }
    /**
     * By setting in your environment config the constant ACP_COLUMNS_WRITABLE to true,
     * we will write all changes to admin columns to files in the plugin.
     *
     * This defaults to false and should definitely be false on anything but temporary
     * local environments where you want to update.
     */
    protected static function is_admin_columns_writeable() : bool
    {
        return \defined('WPPS_Vendor\\ACP_COLUMNS_WRITABLE') ? ACP_COLUMNS_WRITABLE : \false;
    }
}
