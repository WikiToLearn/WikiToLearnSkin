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
    var paths = require('node-neat').includePaths;
    paths.push("/home/gianluca/mediawiki-1.26.2-1/apps/mediawiki/htdocs/skins/WikiToLearnSkin/bower_components/bootstrap/scss");
	return gulp.src(src.sass)
		.pipe(sass({
            includePaths: paths
        }))
        .pipe( gulp.dest(build.sass) );
});

gulp.task('watch', function () {
    gulp.watch('styles/**/*.scss', ['sass'])
})
