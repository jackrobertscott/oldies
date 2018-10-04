'use strict';

var assert = require('chai').assert;
var gulp = require('gulp');
var registry = require('../');

describe('solis-registry', function() {
  before(function () {
    this.gi = new gulp.Gulp();
    this.gi.registry(registry);
  });

  it('should contain compile task', function() {
    assert.ok(typeof this.gi.task('compile') === 'function');
  });

  it('should contain build task', function() {
    assert.ok(typeof this.gi.task('build') === 'function');
  });

  it('should contain serve task', function() {
    assert.ok(typeof this.gi.task('serve') === 'function');
  });

  it('should contain deploy task', function() {
    assert.ok(typeof this.gi.task('deploy') === 'function');
  });
});
