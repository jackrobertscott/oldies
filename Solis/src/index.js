const hub = require('gulp-hub');

module.exports = hub([
  'tasks/build.js',
  'tasks/compile.js',
  'tasks/deploy.js',
  'tasks/images.js',
  'tasks/markups.js',
  'tasks/scripts.js',
  'tasks/serve.js',
  'tasks/styles.js',
]);
