<?php

/**
 * Abstract Custom Post Type Class
 *
 * The abstract custom post type class helps us create a predicable
 * set of features to use when accessing taxonomy data stores.
 *
 * It extends the Data Store interface which ensures predicable
 * CRUD functions available for all types of data, from CPTs
 * to taxonomies or even custom tables.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\Event
 * @since   1.0.0
 */
namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\DataStores;

use WPPS_Vendor\BernskioldMedia\WP\PluginBase\Exceptions\DataStoreException;
\defined('ABSPATH') || exit;
/**
 * Class Custom_Post_Type
 */
abstract class CustomPostType extends DataStoreWP
{
    /**
     * If this is set to false, we automatically deploy a feature to
     * disable the block editor for the data store instead using the
     * classic editor. Should be used sparingly.
     */
    protected static bool $block_editor = \true;
    /**
     * Default permissions for the post type.
     */
    protected static array $default_permissions = ['administrator' => ['read_private' => \true, 'edit' => \true, 'edit_others' => \true, 'publish' => \true, 'delete' => \true], 'editor' => ['read_private' => \true, 'edit' => \true, 'edit_others' => \true, 'edit_published' => \true, 'publish' => \true, 'delete' => \true, 'delete_others' => \true, 'delete_published' => \true], 'author' => ['read_private' => \true, 'edit' => \true, 'edit_others' => \false, 'edit_published' => \true, 'publish' => \true, 'delete' => \true, 'delete_others' => \false, 'delete_published' => \true], 'contributor' => ['read_private' => \true, 'edit' => \true, 'edit_others' => \false, 'edit_published' => \true, 'publish' => \false, 'delete' => \true, 'delete_others' => \false, 'delete_published' => \false], 'subscriber' => ['read_private' => \false, 'create' => \false, 'edit' => \false, 'edit_others' => \false, 'edit_published' => \false, 'publish' => \false, 'delete' => \false, 'delete_others' => \false, 'delete_published' => \false]];
    public function __construct()
    {
        parent::__construct();
        if (\property_exists(static::class, 'block_editor') && \false === static::$block_editor) {
            add_filter('use_block_editor_for_post_type', [static::class, 'disable_block_editor'], 10, 2);
        }
    }
    /**
     * Handle the registration logic here to
     * set up and register the object with WordPress.
     */
    public static abstract function register() : void;
    /**
     * Create Custom Post Type Object
     *
     * @throws DataStoreException
     */
    public static function create(string $name, array $args = []) : int
    {
        /**
         * Check that the required data for creation is set.
         */
        if (!$name) {
            throw new DataStoreException('Tried to create an object, but the object name was not passed in correctly.', $args);
        }
        /**
         * Set up the post data.
         */
        $post_data = wp_parse_args($args, ['post_type' => static::get_key(), 'post_content' => '', 'post_status' => 'publish']);
        /**
         * Create the post!
         */
        $response = wp_insert_post($post_data, \true);
        /**
         * Bail now if we couldn't create.
         */
        if (is_wp_error($response)) {
            throw new DataStoreException('Tried to create an object, but it failed.', ['error' => $response->get_error_message(), 'post_data' => $post_data]);
        }
        return (int) $response;
    }
    /**
     * Updates a post.
     *
     * @throws DataStoreException
     */
    public static function update(int $object_id, array $args = []) : int
    {
        $data = wp_parse_args($args, ['ID' => $object_id, 'post_type' => static::get_key()]);
        $response = wp_update_post($data, \true);
        /**
         * Bail now if we couldn't create.
         */
        if (is_wp_error($response)) {
            throw new DataStoreException('Tried to update an object, but it failed.', ['error' => $response->get_error_message(), 'post_data' => $data]);
        }
        return (int) $response;
    }
    /**
     * Delete an object.
     *
     * @throws DataStoreException
     */
    public static function delete(int $object_id, bool $skip_trash = \false) : bool
    {
        $response = wp_delete_post($object_id, $skip_trash);
        if (\false === $response) {
            throw new DataStoreException('Tried to delete object, but it failed.', ['object_id' => $object_id, 'skip_trash' => $skip_trash]);
        }
        return \true;
    }
    /**
     * Check if posts exists. Returns integer if exists, or null.
     */
    public static function does_object_exist(string $post_title) : ?int
    {
        $post = get_page_by_title($post_title, OBJECT, static::get_key());
        if (null !== $post) {
            return (int) $post->ID;
        }
        return null;
    }
    /**
     * Get and store terms from a taxonomy.
     *
     * @param  Data|integer  $object  Data object or object ID.
     * @param  string  $taxonomy  Taxonomy name e.g. product_cat.
     *
     * @return array of terms
     */
    protected static function get_term_ids($object, string $taxonomy) : array
    {
        if (\is_numeric($object)) {
            $object_id = $object;
        } else {
            $object_id = $object->get_id();
        }
        $terms = get_the_terms($object_id, $taxonomy);
        if (\false === $terms || is_wp_error($terms)) {
            return [];
        }
        return wp_list_pluck($terms, 'term_id');
    }
    /**
     * Get the default capabilities.
     *
     * @return string[]
     */
    protected static function get_capabilities() : array
    {
        $capabilities = ['edit_post' => 'edit_' . static::get_key(), 'read_post' => 'read_' . static::get_key(), 'delete_post' => 'delete_' . static::get_key(), 'create_posts' => 'edit_' . static::get_plural_key()];
        foreach (static::$default_permissions['administrator'] as $permission => $is_granted) {
            $capabilities[$permission . '_posts'] = static::add_key_to_capability($permission);
        }
        return $capabilities;
    }
    /**
     * Disable Block Editor for the current post type.
     */
    public static function disable_block_editor(bool $current_status, string $post_type) : bool
    {
        if (self::get_key() === $post_type) {
            return \false;
        }
        return $current_status;
    }
    public function get_edit_link() : string
    {
        $post_type = get_post_type_object(self::$data_store::get_key());
        if (!$post_type) {
            return '';
        }
        if ($post_type->_edit_link) {
            return admin_url(\sprintf($post_type->_edit_link . '&action=edit', $this->get_id()));
        }
        return '';
    }
}
