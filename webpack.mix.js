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
 * @package BernskioldMedia\Equmeniakyrkan\Equmenisk
 **/

const mix = require('laravel-mix');

const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminPlugin    = require('imagemin-webpack-plugin').default;
const imageminMozjpeg   = require('imagemin-mozjpeg');

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
	scripts: 'assets/scripts',
	styles: 'assets/styles',
	images: 'assets/images',
	fonts: 'assets/fonts',
};

/*
 * Set Laravel Mix options.
 *
 * @link https://laravel-mix.com/docs/5.0/css-preprocessors
 */
mix.options({
	processCssUrls: false,
	postCss: [
		require('postcss-custom-properties')(),
		require('postcss-preset-env')({
			stage: 4,
			browsers: [
				'> 1%',
				'last 2 versions',
				'ie >= 11',
			],
			autoprefixer: {grid: true},
		}),
	],
});

/*
 * Builds sources maps for assets.
 *
 * @link https://laravel.com/docs/5.6/mix#css-source-maps
 */
mix.sourceMaps();

/**
 * Internal JavaScript
 */
mix.js(`${assetPaths.scripts}/src/index.js`,
		`${assetPaths.scripts}/dist/app.js`).
		js(`${assetPaths.scripts}/src/admin.js`,
				`${assetPaths.scripts}/dist/admin-app.js`);

/*
 * Process the SCSS
 *
 * @link https://laravel-mix.com/docs/5.0/css-preprocessors
 * @link https://github.com/sass/dart-sass#javascript-api
 */
const sassConfig = {
	sassOptions: {
		outputStyle: 'compressed',
		indentType: 'tab',
		indentWidth: 1,
	},
};

// Process the scss files.
mix.sass(`${assetPaths.styles}/src/app.scss`, `${assetPaths.styles}/dist`,
		sassConfig).
		sass(`${assetPaths.styles}/src/admin.scss`, `${assetPaths.styles}/dist`,
				sassConfig);

/**
 * Maybe enable sourcemaps
 **/
if (! mix.inProduction()) {
	mix.sourceMaps();
}

/*
 * Custom Webpack Config
 *
 * @link https://laravel.com/docs/6.x/mix#custom-webpack-configuration
 * @link https://webpack.js.org/configuration/
 */
mix.webpackConfig({
	mode: mix.inProduction() ? 'production' : 'development',
	devtool: mix.inProduction() ? '' : 'source-map',
	stats: 'minimal',
	performance: {
		hints: false,
	},
	externals: {
		jquery: 'jQuery',
	},
	plugins: [
		new CopyWebpackPlugin([
			{from: `${assetPaths.images}/src`, to: `${assetPaths.images}/dist`},
		]),
		new ImageminPlugin({
			test: /\.(jpe?g|png|gif|svg)$/i,
			disable: process.env.NODE_ENV !== 'production',
			optipng: {
				optimizationLevel: 3,
			},
			gifsicle: {
				optimizationLevel: 3,
			},
			pngquant: {
				quality: '65-90',
				speed: 4,
			},
			svgo: {
				plugins: [
					{cleanupIDs: false},
					{removeViewBox: false},
					{removeUnknownsAndDefaults: false},
				],
			},
			plugins: [
				imageminMozjpeg({quality: 75}),
			],
		}),
	],
	watchOptions: {
		ignored: /node_modules/,
	},
});
