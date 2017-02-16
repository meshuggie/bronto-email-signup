var gulp = require('gulp');
var gulpSass = require('gulp-sass');
var gulpBrowserify = require("gulp-browserify");
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var mergeStream = require('merge-stream');
var path = require("path");
var rootPath = __dirname;
var runSequence = require('run-sequence');

var dirs = [
  "admin",
  "public"
];

var scssList = dirs.map(function(site) {
  return site + '/assets/scss/**/*.scss';
});
var jsList = dirs.map(function(site) {
  return site + '/assets/js/**/*.js';
});

gulp.task('styles', function() {
  var streams = mergeStream();
  dirs.forEach(function(site) {
    var compiledPath = '/dist/css';
    var srcPath = path.join(rootPath, site, '/assets/scss/**/*.scss'),
      dstPath = path.join(rootPath, site, compiledPath);

    streams.add(gulp.src(srcPath)
      .pipe(gulpSass({outputStyle: 'compressed'}).on('error', gulpSass.logError))
      .pipe(gulp.dest(dstPath))
    );
  });

  return streams;
});

gulp.task('js', function() {
  var streams = mergeStream();
  dirs.forEach(function(site) {
    var compiledPath = '/dist/js';
    var srcPath = path.join(rootPath, site, '/assets/js/**/*.js'),
      dstPath = path.join(rootPath, site, compiledPath);

    streams.add(gulp.src(srcPath)
      .pipe(gulpBrowserify())
      .pipe(concat('bronto-email-signup.js'))
      .pipe(gulp.dest(dstPath))
      .pipe(rename('bronto-email-signup.min.js'))
      .pipe(uglify())
      .pipe(gulp.dest(dstPath))
    );
  });

  return streams;
});

gulp.task('watch', ['styles', 'js'], function() {
  gulp.watch(scssList, ['styles']);
  gulp.watch(jsList, ['js']);
});

gulp.task('default', function(callback) {
  runSequence(['styles', 'js'], 'watch', callback);
});
