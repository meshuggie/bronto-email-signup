var gulp = require('gulp');
var gulpSass = require('gulp-sass');
var mergeStream = require('merge-stream');
var path = require("path");
var rootPath = __dirname;
var runSequence = require('run-sequence');

var dirs = [
    "admin",
    "public"
];

var scssList = dirs.map(function(site) {
    return site + '/scss/**/*.scss';
});

gulp.task('styles', function() {
    var streams = mergeStream();
    dirs.forEach(function(site) {
        var compiledPath = '/css';
        var srcPath = path.join(rootPath, site, '/scss/**/*.scss'),
            dstPath = path.join(rootPath, site, compiledPath);

        streams.add(gulp.src(srcPath)
            .pipe(gulpSass({outputStyle: 'compressed'}).on('error', gulpSass.logError))
            .pipe(gulp.dest(dstPath))
        );
    });

    return streams;
});

gulp.task('watch', ['styles'], function() {
    gulp.watch(scssList, ['styles']);
});

gulp.task('default', function(callback) {
    runSequence('styles', 'watch', callback);
});
