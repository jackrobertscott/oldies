'use strict';

const path = require('path');
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const gulpif = require('gulp-if');
const less = require('gulp-less');
const sass = require('gulp-sass');
const h = require('../helpers');
const config = h.config();

gulp.task('styles', gulp.parallel(
  'css',
  'less',
  'sass'
));

gulp.task('watch:styles', () => {
  gulp.watch(path.join(config.paths.src, '**/*.css'), gulp.series('css', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.less'), gulp.series('less', 'reload'));
  gulp.watch(path.join(config.paths.src, '**/*.{sass,scss}'), gulp.series('sass', 'reload'));
});

gulp.task('css', () => {
  return gulp.src(h.filter(config.paths.src, '.css'))
    .pipe(plumber(h.plumb))
    .pipe(gulpif(config.sourcemaps, sourcemaps.init()))
    .pipe(autoprefixer())
    .pipe(gulpif(config.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('less', () => {
  return gulp.src(h.filter(config.paths.src, '.less'))
    .pipe(plumber(h.plumb))
    .pipe(gulpif(config.sourcemaps, sourcemaps.init()))
    .pipe(less())
    .pipe(autoprefixer())
    .pipe(gulpif(config.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.paths.tmp));
});

gulp.task('sass', () => {
  return gulp.src(h.filter(config.paths.src, '.{sass,scss}'))
    .pipe(plumber(h.plumb))
    .pipe(gulpif(config.sourcemaps, sourcemaps.init()))
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(gulpif(config.sourcemaps, sourcemaps.write()))
    .pipe(gulp.dest(config.paths.tmp));
});
