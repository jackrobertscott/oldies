'use strict';

const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const h = require('../helpers');
const config = h.config();

gulp.task('watch', gulp.parallel(
  'watch:markups',
  'watch:scripts',
  'watch:styles',
  'watch:images',
  'watch:other'
));

gulp.task('reload', () => {
  browserSync.reload();
});

gulp.task('serve', gulp.series('compile', (cb) => {
  browserSync.init({
    server: config.paths.tmp,
  }, cb);
}, 'watch'));

gulp.task('serve:build', gulp.series('build', (cb) => {
  browserSync.init({
    server: config.paths.dist,
  }, cb);
}));
