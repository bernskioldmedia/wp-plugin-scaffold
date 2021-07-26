<?php

/**
 * Abstract Data
 *
 * The abstract data functions sets extends our
 * data interface, which ensures we always have a base
 * set of functions ready.
 *
 * These functions are available whenever we create classes
 * that interface with data, be it Custom Post Types, full
 * custom database tables or taxonomies.
 *
 * We create these "helper classes" to make accessing
 * data very predicable and easy.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\Event
 * @since   1.0.0
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Data;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Interfaces\DataInterface;
\defined('ABSPATH') || exit;
/**
 * Abstract Class Data
 *
 * @package BernskioldMedia\WP\Event
 */
abstract class Data implements DataInterface
{
    /**
     * ID for this object.
     */
    protected int $id = 0;
    /**
     * Reference to the data store.
     */
    protected static string $data_store;
    /**
     * Data constructor.
     *
     * @param  int|object|array  $id
     */
    public function __construct($id = 0)
    {
        if (\is_numeric($id) && $id > 0) {
            $this->set_id($id);
        }
    }
    /**
     * Only store the object ID to avoid serializing the data object instance.
     */
    public function __sleep() : array
    {
        return ['id'];
    }
    /**
     * Re-run the constructor with the object ID.
     *
     * If the object no longer exists, remove the ID.
     */
    public function __wakeup()
    {
        try {
            $this->__construct(absint($this->id));
        } catch (\Exception $e) {
            $this->set_id(0);
        }
    }
    /**
     * Returns the unique ID for this object.
     */
    public function get_id() : int
    {
        return $this->id;
    }
    /**
     * Get Object Key
     */
    public static function get_object_type() : string
    {
        return static::get_data_store()::get_key();
    }
    /**
     * Get data store.
     */
    public static function get_data_store() : string
    {
        return static::$data_store;
    }
    /**
     * Set ID.
     */
    public function set_id(int $id) : void
    {
        $this->id = absint($id);
    }
    /**
     * Get Property
     *
     * @return mixed|null
     */
    protected function get_prop(string $field_key)
    {
        return get_field($field_key, $this->get_id());
    }
    /**
     * Get Date Prop
     */
    protected function get_date_prop(string $field_key, string $format = 'Y-m-d') : ?string
    {
        $date = $this->get_prop($field_key);
        if (!$date) {
            return null;
        }
        return wp_date($format, \strtotime($date));
    }
    /**
     * Get Boolean Prop
     */
    protected function get_bool_prop(string $field_key) : ?bool
    {
        return (bool) $this->get_prop($field_key);
    }
    /**
     * Get Term ID prop from WP Term returning field.
     */
    protected function get_term_id_prop(string $field_key) : ?int
    {
        $object = $this->get_prop($field_key);
        return $object->term_id ?? null;
    }
    /**
     * Get taxonomy property from ACF.
     * If multiple is true we return an array. Otherwise a WP_Term object.
     *
     * @return \WP_Term|array|null
     */
    protected function get_taxonomy_prop(string $data_store, bool $multiple = \false)
    {
        $taxonomy = $data_store::get_key();
        $terms = get_the_terms($this->get_id(), $taxonomy);
        if (!$terms || is_wp_error($terms)) {
            return null;
        }
        if ($multiple) {
            return $terms;
        }
        return $terms[0];
    }
    protected function get_taxonomy_string(string $data_store, string $separator = ', ', string $key = 'name') : string
    {
        $terms = $this->get_taxonomy_prop($data_store, \true);
        if (!$terms) {
            return '';
        }
        $output = [];
        foreach ($terms as $term) {
            $output[] = $term->{$key};
        }
        return \implode($separator, $output);
    }
    /**
     * Check if user can work with the object in question.
     * As type, this function takes: "edit", "delete" and "view".
     *
     * Defaults to current user if no user is given.
     */
    public function can_user(string $type, ?int $user_id = null) : bool
    {
        if (null === $user_id) {
            $user_id = get_current_user_id();
        }
        if (0 === $user_id) {
            return \false;
        }
        switch ($type) {
            case 'edit':
            case 'update':
                $capability = 'edit_' . static::get_data_store()::get_key();
                break;
            case 'delete':
                $capability = 'delete_' . static::get_data_store()::get_key();
                break;
            case 'view':
            case 'access':
            case 'read':
            default:
                $capability = 'read_' . static::get_data_store()::get_key();
                break;
        }
        return user_can($user_id, $capability, $this->get_id());
    }
    /**
     * Find an object.
     *
     * @return static|null
     */
    public static function find(string $name)
    {
        $id = static::get_data_store()::does_object_exist($name);
        if (!$id) {
            return null;
        }
        return new static($id);
    }
    /**
     * Find or Create Object
     *
     * @return static
     */
    public static function find_or_create(string $name)
    {
        $id = static::get_data_store()::does_object_exist($name);
        if ($id) {
            return new static($id);
        }
        $new_id = static::create($name);
        return new static($new_id);
    }
    /**
     * Set property
     *
     * @param  string  $field_key
     * @param  mixed  $new_value
     *
     * @return bool|int|mixed
     */
    protected function set_prop(string $field_key, $new_value)
    {
        return update_field($field_key, $new_value, $this->get_id());
    }
    /**
     * Create an item.
     *
     * @return int|null
     */
    public static function create(string $name, array $args = []) : int
    {
        return static::get_data_store()::create($name, $args);
    }
    /**
     * Update an object.
     *
     * @return mixed
     */
    public static function update(int $object_id, array $args = [])
    {
        return static::get_data_store()::update($object_id, $args);
    }
    /**
     * Delete an item.
     */
    public static function delete(int $object_id, bool $force_delete = \false) : bool
    {
        return static::get_data_store()::delete($object_id, $force_delete);
    }
    /**
     * Convert all metadata to array.
     */
    public function to_array() : array
    {
        if (!static::$data_store || !static::$data_store::$metadata) {
            return [];
        }
        $data = [];
        foreach (static::$data_store::$metadata as $meta) {
            $data[$meta] = $this->{"get_{$data}"};
        }
        return $data;
    }
}
