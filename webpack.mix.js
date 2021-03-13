/**
 * Laravel Mix Configuration
 *
 * We use Laravel Mix as an easy-to-understand interface for webpack,
 * which can otherwise be quite complicated. Mix is super simple and
 * works very well.
 *
 * @link https://laravel.com/docs/6.0/mix
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 **/

const mix = require( 'laravel-mix' );

/**************************************************************
 * Build Process
 *
 * This part handles all the compilation and concatenation of
 * all the theme's resources.
 *************************************************************/

/*
 * Default Options for CSS Processing
 *
 * @link https://laravel-mix.com/docs/6.0/css-preprocessors
 */
mix.options( {
	processCssUrls: false,
	postCss: [
		require( 'postcss-preset-env' )( {
			stage: 3,
			browsers: [
				'> 1%',
				'last 2 versions'
			]
		} )
	]
} );

/*
 * Builds sources maps for assets.
 * if we are not in production.
 *
 * @link https://laravel.com/docs/6.0/mix#css-source-maps
 */
if ( ! mix.inProduction() ) {
	mix.sourceMaps();
}

/**
 * JavaScript
 */
// mix.js( 'assets/scripts/src/index.js', 'assets/scripts/dist/app.js' );
// mix.js( 'assets/scripts/src/admin.js', 'assets/scripts/dist/admin-app.js' );

/*
 * Process the SCSS
 *
 * @link https://laravel-mix.com/docs/6.0/css-preprocessors
 * @link https://github.com/sass/dart-sass#javascript-api
 */
const sassConfig = {
	sassOptions: {
		outputStyle: 'compressed'
	}
};

// mix.sass( 'assets/styles/src/app.scss', 'assets/styles/dist/app.css', sassConfig );
// mix.sass( 'assets/styles/src/admin.scss', 'assets/styles/dist/admin.css', sassConfig );

/*
 * Custom Webpack Config
 *
 * @link https://laravel.com/docs/8.x/mix#custom-webpack-configuration
 * @link https://webpack.js.org/configuration/
 */
mix.webpackConfig( {
	mode: mix.inProduction() ? 'production' : 'development',
	devtool: mix.inProduction() ? '' : 'cheap-source-map',
	stats: 'minimal',
	performance: {
		hints: false
	},
	externals: {
		jquery: 'jQuery'
	},
} );
