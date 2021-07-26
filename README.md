# BM WP Plugin Scaffold

This is a simple plugin base for a WordPress plugin that we use at Bernskiold Media when creating custom plugin's for our clients.

This base is kept extremely simple in order to just facilitate a quick start. The only code bits that we have included are for taxonomies and post types, which are almost always
used in every new plugin.

We will add more (or less) to this plugin base as our development needs change.

In general, refer to our WP Plugin Base documentation for help on how to work with the scaffold.

## Getting Started

To get started developing a plugin, it's easiest to use the `bmwp` CLI.

With it installed, place yourself in the plugins folder and run: `bmwp create:plugin plugin-name`, replacing plugin-name with your desired folder name. You will then be asked a
series of setup questions.

After running it, remove this setion from the README and update it to be more specific about the plugin project.

## Development How-tos

### How to add block editor support

We often find ourselves having to add block support for a plugin that offers a few additional block editor blocks.

To make this simple, we have abstracted a composer library, (Block Plugin Support)[https://github.com/bernskioldmedia/Block-Plugin-Support], that contains a Trait with helper
functions.

Refer to its documentation on how to add it.

#### Where to place blocks?

Block code is expected to be placed in the `blocks/` directory. With a sub-folder for each block.

#### Block Folder Structure

As a general guideline, each block should have the following structure. We prefer splitting into more files for cleanliness and brevity.

```
index.js
edit.js
icon.js
inspector.js
save.js
```

#### Server-side Rendered Block Base

For a server-side rendered block, we have a base block class that provides helpful methods. This is provided by the Block Plugin Support library.

This is how a bare block class could look, inheriting methods from the abstract Block class:

```
use BernskioldMedia\WP\Block_Plugin_Support\Block;

class My_Thing_Block extends Block {

	/**
	 * Render the content
	 *
	 * @param  array  $attributes
	 *
	 * @return false|string
	 */
	 public static function render( $attributes ): string {

	 	ob_start();
	 	?>
	 		<!-- My HTML Output Here -->
	 	<?php
	 	return ob_get_clean();

	 }

}
```

This file should be placed under: `src/Blocks/My_Thing_Block.php`.

#### Metadata in REST API

It's helpful to make our ACF and other metadata available in the REST API. For this we have a series of helper methods that hook into the REST API to handle returning and updating
metadata.

You need to do two things:

1. Connect the data store to the data class, by referecing the data class. `protected static $data_class = Namespace\MyObject::class`.
2. Set up an array of metadata to make available in the `public static $metadata = []` array. These should be the same keys as used by the getters/setters in the data class. For
   example, if you have a `get_name()` method in the data class, add `name` to the metadata array.

#### Modifying the default WP_Query for archive/listings.

By adding the method `public static query_modifications( \WP_Query $query ): \WP_Query` to the data store class, it will automatically be hooked in to `pre_get_posts`, allowing you
to modify the main query for the data store easily.

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

You may override by setting a similarily structured `role => permissions` array on the `::$permissions` property. Anything in the permissions property will override the default
permissions set by `::$default_permissions`.

You can load the capabilities array for the register function by calling `self::get_capabilities()`.

## Coding Style

We exclusively try and use the WordPress coding practices for all languages, which includes PHP, HTML, CSS and JavaScript.

This base plugin is in part OOP based to both live in its own namespace, and to make extending, updating and maintaining code simple and easy.

## Open Source

The reason we are open sourcing it is because it's the right thing to do. Perhaps someone finds a use out of it all, or parts. This is not going to be an actively developed "master
plugin base" that is set out to be the next greatest thing in the WordPress community. It's just going to be what we use internally, just published for the world to see and use as
you see fit.
