# BM WP Plugin Scaffold
This is a simple plugin base for a WordPress plugin that we use at
Bernskiold Media when creating custom plugin's for our clients.

This base is kept extremely simple in order to just facilitate a quick start.
The only code bits that we have included are for taxonomies and post types,
which are almost always used in every new plugin.

We will add more (or less) to this plugin base as our development needs change.

## Getting Started
To get started developing a plugin, perform the following steps:

1. Download the package from GitHub. This will get you the folders you need, while excluding the development folders and files that you don't need when this is part of a master Git repo.
2. Rename the `wp-plugin-scaffold.php` file and `wp-plugin-scaffold` directory.
3. Update the plugin details in the master plugin file (previously `wp-plugin-scaffold.php`).
4. Update `composer.json` and `package.json` with your plugin information.
5. Search and replace `wp_plugin_scaffold` with your plugin name.
6. Search and replace the prefix `wpps_` with your own plugin prefix.
7. Search and replace the textdomain `wp-plugin-scaffold`. This should match the plugin master file name.
8. Serach and replace the namespace `BernskioldMedia\WP\PluginScaffold` with your own namespace. At Bernskiold Media all client projects have a namespace `BernskioldMedia\CLIENTNAME\PACKAGENAME`. the `WP` is reserved for our shared WordPress packages.
9. Search and replace the plugin class name `WP_Plugin_Scaffold` with your own plugin classname.
10. Initialize composer and NPM by running `npm install && composer install` in the terminal in the plugin directory.

## Development How-tos

### How to add a Data Store
Adding a new data store is simple. The scaffold has built-in support for both taxonomies and custom post types.
Both of these share common methods from the `Abstracts\Data_Store_WP` class, and then type-specific common
methods from either `Abstracts\Custom_Post_Type` or `Abstracts\Taxonomy`.

In the `src/data-stores` folder, you will find examples of a custom post type and taxonomy implementation.

There are a number of "magic methods" available to define in the data store which are registered and hooked in peroperly if they are defined in your data store class.

#### Adding ACF Fields
By adding a `public static function fields()` to the data store and defining the ACF fields there using PHP, the structure will automatically hook them into the right load order.

#### Metadata in REST API
It's helpful to make our ACF and other metadata available in the REST API. For this we have a series of helper methods that hook into the REST API to handle returning and updating metadata.

You need to do two things:

1. Connect the data store to the data class, by referecing the data class. `protected static $data_class = Namespace\MyObject::class`.
2. Set up an array of metadata to make available in the `public static $metadata = []` array. These should be the same keys as used by the getters/setters in the data class. For example, if you have a `get_name()` method in the data class, add `name` to the metadata array.

#### Modifying the default WP_Query for archive/listings.
By adding the method `public static query_modifications( \WP_Query $query ): \WP_Query` to the data store class, it will automatically be hooked in to `pre_get_posts`, allowing you to modify the main query for the data store easily.

This can be useful for example to set a specific sort order for the data store data.

Use the function below here for a quick-start example:

```
/**
 * Modify the query.
 *
 * @param  \WP_Query  $query
 *
 * @return \WP_Query
 */
public static function query_modifications( \WP_Query $query ) {

	if ( is_admin() && ! $query->is_main_query() ) {
		return $query;
	}

	if ( $query->get( 'post_type' ) !== static::get_key() ) {
		return $query;
	}

	// $query->set( 'prop', 'value' );

	return $query;

}
```

#### Setting Capabilities
By default, the data store will have a common-sense default permission structure. The abstract `Custom_Post_Type` and `Taxonomy` classes house the default settings.

You may override by setting a similarily structured `role => permissions` array on the `::$permissions` property. Anything in the permissions property will override the default permissions set by `::$default_permissions`.

You can load the capabilities array for the register function by calling `self::get_capabilities()`.

## Coding Style
We exclusively try and use the WordPress coding practices for all languages,
which includes PHP, HTML, CSS and JavaScript.

This base plugin is in part OOP based to both live in its own namespace,
and to make extending, updating and maintaining code simple and easy.

## Open Source
The reason we are open sourcing it is because it's the right thing to do.
Perhaps someone finds a use out of it all, or parts. This is not going to be
an actively developed "master plugin base" that is set out to be the next
greatest thing in the WordPress community. It's just going to be what
we use internally, just published for the world to see and use as you see fit.
