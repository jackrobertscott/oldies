'use strict';

const path = require('path');
const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
const sequence = require('run-sequence');
const h = require('../helpers');
const config = h.config();

gulp.task('images', () => {
  return gulp.src(h.filter(config.paths.src, '.{gif,jpeg,jpg,png,svg}'))
    .pipe(imagemin())
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('watch:images', () => {
  gulp.watch(path.join(config.paths.src, '**/*.{gif,jpeg,jpg,png,svg}'), function(cb) {
    sequence('images', 'reload', cb);
  });
});
