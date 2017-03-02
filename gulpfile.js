var gulp = require('gulp'),
    apidoc = require('gulp-apidoc');

gulp.task('apidoc', function(done){
    apidoc({
        src: "app/Http/Controllers/",
        dest: "public/documentation/v1/",
    }, done);
});

/**
 * Watch Controller's changes
 */
gulp.watch('app/Http/Controllers/**/*.php', ['apidoc']);

/**
 * Default Task
 */
gulp.task('default', ['apidoc']);