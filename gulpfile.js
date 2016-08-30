var gulp = require('gulp');
var sass = require('gulp-sass');

var src = {
    sass: './styles/skin.scss',
    images: './images/**/*',
    js: './scripts/**/*.scss'
};

var build = {
	sass: './build/styles/'
}

gulp.task('sass', function() {
	return gulp.src(src.sass)
		.pipe(sass({
            includePaths: require('node-neat').includePaths
        }))
        .pipe( gulp.dest(build.sass) );
});
