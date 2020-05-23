/**
 * Laravel Mix Configuration
 *
 * We use Laravel Mix as an easy-to-understand interface for webpack,
 * which can otherwise be quite complicated. Mix is super simple and
 * works very well.
 *
 * @link https://laravel.com/docs/5.6/mix
 *
 * @author  Bernskiold Media <info@bernskioldmedia.com>
 **/

const mix = require( "laravel-mix" );

/**************************************************************
 * Build Process
 *
 * This part handles all the compilation and concatenation of
 * all the theme's resources.
 *************************************************************/

/*
 * Asset Directory Path
 */
const assetPaths = {
	scripts: "assets/scripts",
	styles: "assets/styles",
	images: "assets/images",
	fonts: "assets/fonts"
};

/*
 * Default Options for CSS Processing
 *
 * @link https://laravel-mix.com/docs/5.0/css-preprocessors
 */
mix.options( {
	processCssUrls: false,
	postCss: [
		require( "postcss-preset-env" )( {
			stage: 3,
			browsers: [
				"> 1%",
				"last 2 versions"
			]
		} )
	]
} );

/*
 * Builds sources maps for assets.
 * if we are not in production.
 *
 * @link https://laravel.com/docs/5.6/mix#css-source-maps
 */
if ( ! mix.inProduction() ) {
	mix.sourceMaps();
}

/**
 * Internal JavaScript
 */
mix.js(
	`${assetPaths.scripts}/src/index.js`,
	`${assetPaths.scripts}/dist/app.js`
   )
   .js(
	   `${assetPaths.scripts}/src/admin.js`,
	   `${assetPaths.scripts}/dist/admin-app.js`
   );

/**
 * Vendor JavaScript
 */
/*****
 mix.js( [
	"node_modules/@fortawesome/fontawesome-pro/js/all.js"
], `${assetPaths.scripts}/dist/vendor.js` );
 *****/

/*
 * Process the SCSS
 *
 * @link https://laravel-mix.com/docs/5.0/css-preprocessors
 * @link https://github.com/sass/dart-sass#javascript-api
 */
const sassConfig = {
	sassOptions: {
		outputStyle: "compressed"
	}
};

// Process the scss files.
mix.sass(
	`${assetPaths.styles}/src/app.scss`,
	`${assetPaths.styles}/dist`,
	sassConfig
   )
   .sass(
	   `${assetPaths.styles}/src/admin.scss`,
	   `${assetPaths.styles}/dist`,
	   sassConfig
   );

// Process a version for IE11.
if ( mix.inProduction() ) {
	mix.sass(
		`${assetPaths.styles}/src/app-ie11.scss`,
		`${assetPaths.styles}/dist`,
		sassConfig,
		[
			require( "postcss-custom-properties" )(),
			require( "postcss-preset-env" )( {
				stage: 4,
				browsers: [
					"> 1%",
					"last 2 versions",
					"ie >= 11"
				],
				autoprefixer: { grid: true }
			} )
		]
	);
}

/*
 * Custom Webpack Config
 *
 * @link https://laravel.com/docs/6.x/mix#custom-webpack-configuration
 * @link https://webpack.js.org/configuration/
 */
mix.webpackConfig( {
	mode: mix.inProduction() ? "production":"development",
	devtool: mix.inProduction() ? "":"cheap-source-map",
	stats: "minimal",
	performance: {
		hints: false
	},
	externals: {
		jquery: "jQuery"
	},
	watchOptions: {
		ignored: /node_modules/
	}
} );
