var gulp = require('gulp');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var autoprefixer = require('gulp-autoprefixer');

var browserSync = require('browser-sync').create();

var src = {
    sass: './styles/skin.scss',
    fonts: './styles/fonts/*.scss',
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
    var paths = [];
    paths.push("bower_components/");
	return gulp.src([src.sass, src.fonts])
		.pipe(sass({
            includePaths: paths
        }))
        .on('error', sass.logError)
        .pipe(autoprefixer())
        .pipe( gulp.dest(build.sass));
});

gulp.task('watch', ['sass'], function () {
    gulp.watch('styles/**/*.scss', ['sass'])
})

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "it.tuttorotto.biz"
    });

    gulp.watch("WikiToLearnSkin.php").on('change', browserSync.reload);
    gulp.watch("build/**/*").on('change', browserSync.reload);
    gulp.watch("images/**/*").on('change', browserSync.reload);
});