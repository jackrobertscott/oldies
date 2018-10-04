'use strict';

const path = require('path');
const gulp = require('gulp');
const filter = require('gulp-filter');
const inject = require('gulp-inject');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const csso = require('gulp-csso');
const minify = require('gulp-minify-html');
const del = require('del');
const h = require('../helpers');
const config = h.config();

gulp.task('clean:build', () => {
  return del(config.paths.dist);
});

gulp.task('compress', gulp.series('compile', () => {
  const htmlFilter = filter('**/*.html', {
    restore: true,
  });
  const jsFilter = filter(['**/*.js', '!vendor/*.js'], {
    restore: true,
  });
  const jsVendor = filter('vendor/*.js', {
    restore: true,
  });
  const cssFilter = filter(['**/*.css', '!vendor/*.css'], {
    restore: true,
  });
  const cssVendor = filter('vendor/*.css', {
    restore: true,
  });

  return gulp.src(h.filter(config.paths.tmp, ''))
    .pipe(htmlFilter)
    .pipe(minify({
      comments: true,
      conditionals: true,
      spare: true,
    }))
    .pipe(htmlFilter.restore)
    .pipe(jsFilter)
    .pipe(concat('scripts.min.js'))
    .pipe(uglify())
    .pipe(jsFilter.restore)
    .pipe(jsVendor)
    .pipe(concat('vendor.min.js'))
    .pipe(uglify())
    .pipe(jsVendor.restore)
    .pipe(cssFilter)
    .pipe(concat('styles.min.css'))
    .pipe(csso())
    .pipe(cssFilter.restore)
    .pipe(cssVendor)
    .pipe(concat('vendor.min.css'))
    .pipe(csso())
    .pipe(cssVendor.restore)
    .pipe(gulp.dest(config.paths.dist));
}));

gulp.task('inject:build', () => {
  const sources = gulp.src([
    path.join(config.paths.dist, '**/*.js'),
    path.join(config.paths.dist, '**/*.css'),
    '!' + path.join(config.paths.dist, 'vendor/*.js'),
    '!' + path.join(config.paths.dist, 'vendor/*.css'),
  ], {
    read: false,
  });
  const vendor = gulp.src([
    path.join(config.paths.dist, 'vendor/*.js'),
    path.join(config.paths.dist, 'vendor/*.css'),
  ], {
    read: false,
  });

  return gulp.src(path.join(config.paths.dist, '**/*.html'))
    .pipe(inject(sources, {
      relative: true,
    }))
    .pipe(inject(vendor, {
      relative: true,
      name: 'bower',
    }))
    .pipe(gulp.dest(config.paths.dist));
});

gulp.task('build', gulp.series('clean:build', 'compress', 'inject:build'));
