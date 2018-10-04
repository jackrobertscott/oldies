'use strict';

const path = require('path');
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const gulpif = require('gulp-if');
const sourcemaps = require('gulp-sourcemaps');
const coffee = require('gulp-coffee');
const babel = require('gulp-babel');
const h = require('../helpers');
const config = h.config();

gulp.task('scripts', gulp.parallel(
  'js',
  'coffee',
  'es6'
));

gulp.task('watch:scripts', () => {
  gulp.watch(path.join(config.paths.src, '**/*.js'), gulp.series('js', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.coffee'), gulp.series('coffee', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.es6'), gulp.series('es6', 'reload'));
});

gulp.task('js', () => {
  return gulp.src(h.filter(config.paths.src, '.js'))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('coffee', () => {
  return gulp.src(h.filter(config.paths.src, '.coffee'))
    .pipe(plumber(h.plumb))
    .pipe(gulpif(config.sourcemaps, sourcemaps.init()))
    .pipe(coffee())
    .pipe(gulpif(config.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('es6', () => {
  return gulp.src(h.filter(config.paths.src, '.es6'))
    .pipe(plumber(h.plumb))
    .pipe(gulpif(config.sourcemaps, sourcemaps.init()))
    .pipe(babel({
      presets: ['es2015'],
    }))
    .pipe(gulpif(config.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.paths.tmp));
});
