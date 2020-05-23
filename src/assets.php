<?php
/**
 * Handles the loading of scripts and styles for the
 * theme through the proper enqueuing methods.
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 * @package BernskioldMedia\WP\PluginScaffold
 * @since   1.0.0
 **/

namespace BernskioldMedia\WP\PluginScaffold;

defined( 'ABSPATH' ) || exit;

/**
 * Assets Class
 *
 * @package BernskioldMedia\WP\PluginScaffold
 */
class Assets {

	/**
	 * Assets Constructor
	 */
	public static function init(): void {

		// Styles.
		add_action( 'wp_enqueue_scripts', [ self::class, 'public_styles' ] );
		add_action( 'admin_enqueue_scripts', [ self::class, 'admin_styles' ] );

		// Scripts.
		add_action( 'wp_enqueue_scripts', [ self::class, 'public_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ self::class, 'admin_scripts' ] );

	}

	/**
	 * Registers and enqueues public stylesheets.
	 **/
	public static function public_styles() {

		/**
		 * Register Main Stylesheet.
		 */
		wp_register_style( 'wp-plugin-scaffold-public', Plugin::get_assets_url() . '/styles/dist/app.css', false, Plugin::get_version(), 'all' );

		/**
		 * Enqueue Stylesheets.
		 */
		wp_enqueue_style( 'wp-plugin-scaffold-public' );

	}

	/**
	 * Registers and enqueues plugin admin stylesheets.
	 **/
	public static function admin_styles() {

		/**
		 * Register Main Stylesheet.
		 */
		wp_register_style( 'wp-plugin-scaffold-admin', Plugin::get_assets_url() . '/styles/dist/admin.css', false, Plugin::get_version(), 'all' );

		/**
		 * Enqueue Stylesheets.
		 */
		wp_enqueue_style( 'wp-plugin-scaffold-admin' );

	}

	/**
	 * Enqueue Scripts on public side
	 *
	 * We want to allow the use of good script debugging here too,
	 * so be mindful and use the SCRIPTS_DEBUG constant
	 * to load both minified for production and non-minified files
	 * for testing purposes.
	 **/
	public static function public_scripts() {

		/**
		 * Register the main, minified
		 * and compiled script file.
		 */
		wp_register_script( 'wp-plugin-scaffold-app', Plugin::get_assets_url() . '/scripts/dist/app.js', [ 'jquery' ], Plugin::get_version(), true );

		// Enqueue.
		wp_enqueue_script( 'wp-plugin-scaffold-app' );

	}

	/**
	 * Enqueue Scripts on admin side
	 *
	 * We want to allow the use of good script debugging here too,
	 * so be mindful and use the SCRIPTS_DEBUG constant
	 * to load both minified for production and non-minified files
	 * for testing purposes.
	 **/
	public static function admin_scripts() {

		/**
		 * Register the main, minified
		 * and compiled script file.
		 */
		wp_register_script( 'wp-plugin-scaffold-admin', Plugin::get_assets_url() . '/scripts/dist/admin.js', [ 'jquery' ], Plugin::get_version(), true );

		// Enqueue.
		wp_enqueue_script( 'wp-plugin-scaffold-admin' );

	}
}

Assets::init();
