// Gulp Configuration
'use strict';

const

	images     = {
		src:   'assets/images/**/*',
		build: 'assets/images/'
	},

	css        = {
		src:        'assets/css/scss/style.scss',
		watch:      'scss/**/*',
		build:      'assets/css',
		sassOpts:   {
			outputStyle:     'nested',
			imagePath:       images.build,
			precision:       3,
			errLogToConsole: true
		},
		processors: [
			require('postcss-assets')({
				loadPaths: ['assets/images/'],
				basePath:  '/',
			}),
			require('autoprefixer')({
				browsers: ['last 2 versions', '> 2%']
			}),
			require('css-mqpacker'),
			require('cssnano')
		]
	},

	js         = {
		src:      'assets/js/**/*',
		build:    'assets/js/dist/',
		filename: 'scripts.js'
	},

	// Gulp and plugins
	gulp       = require('gulp'),
	gutil      = require('gulp-util'),
	newer      = require('gulp-newer'),
	imagemin   = require('gulp-imagemin'),
	sass       = require('gulp-sass'),
	postcss    = require('gulp-postcss'),
	deporder   = require('gulp-deporder'),
	concat     = require('gulp-concat'),
	stripdebug = require('gulp-strip-debug'),
	uglify     = require('gulp-uglify')
;

// Browser-sync
var browsersync = false;

// CSS processing
gulp.task('css', () => {
	return gulp.src(css.src)
		.pipe(sass(css.sassOpts))
		.pipe(postcss(css.processors))
		.pipe(gulp.dest(css.build))
		.pipe(browsersync ? browsersync.reload({stream: true}) : gutil.noop());
});

// image processing
gulp.task('images', () => {
	return gulp.src(images.src)
		.pipe(newer(images.build))
		.pipe(imagemin())
		.pipe(gulp.dest(images.build));
});

// JavaScript processing
gulp.task('js', () => {

	return gulp.src(js.src)
		.pipe(deporder())
		.pipe(concat(js.filename))
		.pipe(stripdebug())
		.pipe(uglify())
		.pipe(gulp.dest(js.build))
		.pipe(browsersync ? browsersync.reload({stream: true}) : gutil.noop());

});

// Build Task.
gulp.task('build', ['css', 'js', 'images']);