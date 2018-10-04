'use strict';

const path = require('path');
const _ = require('lodash');
const gutil = require('gulp-util');

module.exports = {
  config() {
    let data;

    try {
      data = require(path.join(process.cwd(), 'config'));
    } catch (e) {}

    _.defaults(data, {
      paths: {
        src: 'src',
        tmp: '.tmp',
        dist: 'dist',
      },
      sourcemaps: false,
      cname: '',
    });

    return data;
  },

  plumb(err) {
    gutil.beep();
    gutil.log(err);
    this.emit('end');
  },

  filter(dir, ext) {
    return [
      path.join(dir, '**/*' + ext),
      '!' + path.join(dir, '**/_*{/**,}'),
    ];
  },

  uncached(_module) {
    delete require.cache[require.resolve(_module)];
    return require(_module);
  }
};
