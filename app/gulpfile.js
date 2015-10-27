var gulp = require('gulp'),
    uglify = require('gulp-uglify'),
    uglifycss = require('gulp-uglifycss'),
    concat = require('gulp-concat');

gulp.task('minify-js', function () {
    return gulp.src([
			'node_modules/angular/angular.min.js',
			'node_modules/jquery/dist/jquery.min.js',
			'node_modules/jquery-mobile/dist/jquery.mobile.min.js',
			'node_modules/underscore/underscore-min.js',
			'Scripts/Common/Helpers.js',
			'Scripts/Services/*.js',
			'Scripts/Controllers/*.js',
			'Scripts/Modules/*.js',
			'Scripts/config.js']).
		pipe(concat('app.js')).
		pipe(uglify({
            mangle: false
        })).
		pipe(gulp.dest('../js/'));
});

gulp.task('minify-css', function () {
    return gulp.src([
			'node_modules/jquery-mobile/dist/jquery.mobile.min.css', 
			'Styles/Styles.css']).
		pipe(concat('app.css')).
		pipe(uglifycss()).
		pipe(gulp.dest('../css/'));
});

gulp.task('default', ['minify-js', 'minify-css']);