'use strict';

const path = require('path');
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const data = require('gulp-data');
const jade = require('gulp-jade');
const nunjucks = require('gulp-nunjucks-render');
const h = require('../helpers');
const config = h.config();

gulp.task('markups', gulp.parallel(
  'html',
  'jade',
  'nunjucks'
));

gulp.task('watch:markups', () => {
  gulp.watch(path.join(config.paths.src, '**/*.html'), gulp.series('html', 'inject:compile', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.jade'), gulp.series('jade', 'inject:compile', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.nunjucks'), gulp.series('nunjucks', 'inject:compile', 'reload'));
});

gulp.task('html', () => {
  return gulp.src(h.filter(config.paths.src, '.html'))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('jade', () => {
  return gulp.src(h.filter(config.paths.src, '.jade'))
    .pipe(plumber(h.plumb))
    .pipe(data(function(file) {
      try {
        return h.requireUncached(path.join(path.dirname(file.path), path.basename(file.path, path.extname(file.path)) + '.json'));
      } catch(e) {}
    }))
    .pipe(jade({
      pretty: true,
    }))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('nunjucks', () => {
  return gulp.src(h.filter(config.paths.src, '.nunjucks'))
    .pipe(plumber(h.plumb))
    .pipe(data(function(file) {
      return h.requireUncached(path.join(path.dirname(file.path), path.basename(file.path, path.extname(file.path)) + '.json'));
    }))
    .pipe(nunjucks())
    .pipe(gulp.dest(config.paths.tmp));
});
