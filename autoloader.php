<?php
/**
 * WordPress Class Autoloader
 */
spl_autoload_register( function ( $classname ) {

	// Remove base.
	$classname = str_replace( 'BernskioldMedia\\WP\\PluginScaffold\\', '', $classname );

	// Regular
	$class     = str_replace( '\\', DIRECTORY_SEPARATOR, strtolower( $classname ) );
	$classpath = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $class . '.php';

	// WordPress
	$parts   = explode( '\\', $classname );
	$class   = strtolower( array_pop( $parts ) );
	$class   = str_replace( '_', '-', $class );
	$folders = strtolower( implode( DIRECTORY_SEPARATOR, $parts ) );
	$folders = str_replace( '_', '-', $folders );
	$wppath  = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $folders . DIRECTORY_SEPARATOR . $class . '.php';

	if ( file_exists( $classpath ) ) {
		require_once $classpath;
	} elseif ( file_exists( $wppath ) ) {
		require_once $wppath;
	}

} );
