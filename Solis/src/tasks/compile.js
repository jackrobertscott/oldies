'use strict';

const path = require('path');
const gulp = require('gulp');
const bowerFiles = require('main-bower-files');
const inject = require('gulp-inject');
const del = require('del');
const h = require('../helpers');
const config = h.config();

gulp.task('compile', gulp.series(
  'clean:compile',
  gulp.parallel(
    'markups',
    'scripts',
    'styles',
    'images',
    'other',
    'bower'
  ),
  'inject:compile'
));

gulp.task('bower', () => {
  return gulp.src(bowerFiles())
    .pipe(gulp.dest(path.join(config.paths.tmp, 'vendor')));
});

gulp.task('inject:compile', () => {
  const sources = gulp.src([
    path.join(config.paths.tmp, '**/*.js'),
    path.join(config.paths.tmp, '**/*.css'),
    '!' + path.join(config.paths.tmp, 'vendor/*.js'),
    '!' + path.join(config.paths.tmp, 'vendor/*.css'),
  ], {
    read: false,
  });
  const vendor = gulp.src([
    path.join(config.paths.tmp, 'vendor/*.js'),
    path.join(config.paths.tmp, 'vendor/*.css'),
  ], {
    read: false,
  });

  return gulp.src(path.join(config.paths.tmp, '**/*.html'))
    .pipe(inject(sources, {
      relative: true,
    }))
    .pipe(inject(vendor, {
      relative: true,
      name: 'bower',
    }))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('clean:compile', () => {
  return del(config.paths.tmp);
});
