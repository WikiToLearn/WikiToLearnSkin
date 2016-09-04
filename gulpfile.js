var gulp = require('gulp');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');

var src = {
    sass: './styles/skin.scss',
    images: './images/**/*',
    js: './scripts/**/*.scss'
};

var build = {
	sass: './build/styles/'
}

function handleError(error) {
    console.log(error.toString());
}

gulp.task('sass', function() {
    var paths = require('node-neat').includePaths;
    paths.push("bower_components/bootstrap/scss");
	return gulp.src(src.sass)
		.pipe(sass({
            includePaths: paths
        }))
        .on('error', sass.logError)
        .pipe( gulp.dest(build.sass) );
});

gulp.task('watch', ['sass'], function () {
    gulp.watch('styles/**/*.scss', ['sass'])
})
