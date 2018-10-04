#!/usr/bin/env node

/* jshint node:true */
'use strict';

/**
 * Dependencies
 */
var fs = require('fs');
var _path = require('path');
var _ = require('lodash');
var program = require('commander');
var pkg = require('./package');
var pipes = [];
var tree = '.\n';

/**
 * Parse command options
 */
program.version(pkg.version)
  .option('-d, --dir <path>', 'root directory to start tree')
  .option('-o, --output [name]', 'write to file name of the output file')
  .option('-c, --clean', 'clean program of any default values')
  .option('-l, --no-log', 'log the tree to the console')
  // TODO make --ignore option accept paths rather than names
  .option('-r, --no-rec <names>', 'folder names to output but not recurse', function(val) {
    return val.split(',').map(_.trim);
  })
  .option('-i, --ignore <names>', 'file and folder names to ignore', function(val) {
    return val.split(',').map(_.trim);
  })
  .parse(process.argv);

/**
 * Set config
 */
var config = {
  dir: (program.dir) ? _path.join(process.cwd(), program.dir) : process.cwd(),
  ignore: (program.clean) ? _.union([], program.ignore) : _.union([
    '.git',
    '.DS_Store'
  ], program.ignore),
  noRec: (program.clean) ? _.union([], program.rec) : _.union([
    'node_modules',
    'bower_components'
  ], program.rec),
  output: (typeof program.output === 'string') ? program.output : 'tree.it'
};

/**
 * Build tree and output
 */
build(config.dir, 0, function() {
  if (program.log) {
    console.log(tree);
  }
  if (program.output) {
    fs.writeFile(config.output, tree, function(err) {
      if (err) throw err;
    });
  }
});

/**
 * Recursively traverse down folders building tree
 */
function build(path, lvl, cb) {
  pipes[lvl] = 1;
  fs.readdirSync(path)
    .forEach(function(file, i, a) {
      if (config.ignore.indexOf(file) === -1) {
        // create line
        var line = '';
        for (var n = 0; n < lvl; n++) {
          line += (pipes[n]) ? '|   ' : '    ';
        }
        line += '+-- ' + file;

        // output
        tree += line + '\n';

        // recurse down tree
        var subpath = _path.join(path, file);
        if (fs.lstatSync(subpath).isDirectory() && config.noRec.indexOf(file) === -1) {
          if (i === a.length - 1) pipes[lvl] = 0;
          build(subpath, lvl + 1, _.noop);
        }
      }
    });
  cb();
}
