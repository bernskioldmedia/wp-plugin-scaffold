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