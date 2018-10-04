'use strict';

const path = require('path');
const gulp = require('gulp');
const gulpif = require('gulp-if');
const file = require('gulp-file');
const ghPages = require('gulp-gh-pages');
const h = require('../helpers');
const config = h.config();

gulp.task('deploy', gulp.series('build', () => {
  return gulp.src(path.join(config.paths.dist, '**'))
    .pipe(gulpif(!!config.cname, file.bind(null, 'CNAME', config.cname)))
    .pipe(ghPages());
}));

gulp.task('deploy:uncompressed', gulp.series('compile', () => {
  return gulp.src(path.join(config.paths.tmp, '**'))
    .pipe(gulpif(!!config.cname, file.bind(null, 'CNAME', config.cname)))
    .pipe(ghPages());
}));
