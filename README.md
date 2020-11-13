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
2. Run the setup script `npm run init`. It'll prompt you for a series of configuration questions and then set everything up.
3. Remove this setion from the README and update it to be more specific about the plugin project.

Note: When you run `npm run init` it should set the setup-script in the bin folder as executable on your system. If you still get permission denied errors, make it executable by running `chmod +x ./bin/init.sh`

## Development How-tos

### How to add block editor support
We often find ourselves having to add block support for a plugin that offers a few additional block editor blocks.

To make this simple, we have abstracted a composer library, (Block Plugin Support)[https://github.com/bernskioldmedia/Block-Plugin-Support], that contains a Trait with helper functions.

1. First, require the composer package `composer require bernskioldmedia/block-plugin-support`.
2. In `plugin.php` add the `use Has_Blocks` trait.
3. Add the `public function blocks(): void` method to the Plugin class (see below).
4. Load the blocks by adding `$this->load_blocks( $prefix )` to your `__construct()` method. The prefix is the same one that is defined in every block name. We often use `bm`.
5. In your `webpack.mix.js` file, add the block source files to the build process (see below).

#### Where to place blocks?
Block code is expected to be placed in the `src/blocks` directory. With a sub-folder for each block.

#### How to register a block
Each block needs to be registered in the `blocks()` method of the Plugin class. There are three scenarios that are supported:

```
// Add a pure JavaScript block.
$this->add_block( 'block-name' );

// Add a PHP rendered block.
$this->add_block( 'block-name', [
	'render_callback' => [ Block_Class::class, 'render' ],
	'attributes' => Block_Class::get_attributes(),
] );

// Add a block if a class exists.
// $attributes is only necessary for PHP rendered blocks.
$this->add_block_if( 'Class_Name', 'block-name', $attributes );
```

#### Block Folder Structure
As a general guideline, each block should have the following structure. We prefer splitting into more files for cleanliness and brevity.

```
block-name.js
edit.js
icon.js
inspector.js
save.js
```

If it is a PHP-rendered block, you should have a `blockname-block.php` file too.

#### Server-side Rendered Block Base
For a server-side rendered block, we have a base block class that provides helpful methods. This is provided by the Block Plugin Support library.

This is how a bare block class could look, inheriting methods from the abstract Block class:

```
use BernskioldMedia\WP\Block_Plugin_Support\Block;

class My_Thing_Block extends Block {

	/**
	 * Since this is a dynamically generated block, we
	 * define the attributes here using PHP.
	 *
	 * @var array
	 */
	protected static $attributes = [];

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

### How to add a Data Store
Adding a new data store is simple. The scaffold has built-in support for both taxonomies and custom post types.
Both of these share common methods from the `Abstracts\Data_Store_WP` class, and then type-specific common
methods from either `Abstracts\Custom_Post_Type` or `Abstracts\Taxonomy`.

In the `src/data-stores` folder, you will find examples of a custom post type and taxonomy implementation.

There are a number of "magic methods" available to define in the data store which are registered and hooked in peroperly if they are defined in your data store class.

Finally, when you are ready to load it, add the Data Store class reference to the `$data_stores` property in the main Plugin class, like this:

```
use Namespace\Data_Stores;

class Plugin {

	// Other code...

	public static $data_stores = [
		Data_Stores\My_Data_Store::class,
	];

}

```

#### Adding ACF Fields
By adding a `public static function fields()` to the data store and defining the ACF fields there using PHP, the structure will automatically hook them into the right load order.

When pasting in the code given by ACFs PHP export utility, make sure that you update with the following:

1. Wrap labels, descriptions and instructions in translation functions `__()` so that everything can be localized.
2. Replace the explicit post type definition when loading fields for a specific post type only with `self::get_key()` instead of the hard-coded key.
3. If you have any post_object or relationship fields, consider updating their post_type references with the `Data_Store::get_key()` method instead of the hard-coded post type key.

For number 2, the location block for fields in a specific post type would read:

```
'location' => [
	[
		[
			'param'    => 'post_type',
			'operator' => '==',
			'value'    => self::get_key(),
		],
	],
],
```

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
