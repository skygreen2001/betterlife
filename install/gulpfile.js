/* jshint node: true, strict: true */
'use strict';

/*=====================================
=        Default Configuration        =
=====================================*/

// Please use config.js to override these selectively:

var config = {
  dest: '../bin',
  clean: {
    template_cache_dir: "../home/**/tmp/templates_c/**",
    mac_ignore_file: "../**/.DS_Store"
  }
};

if (require('fs').existsSync('../config.js')) {
  var configFn = require('../config');
  configFn(config);
}
/*-----  End of Configuration  ------*/


/*========================================
=            Requiring stuffs            =
========================================*/

var gulp           = require('gulp'),

    bower          = require('gulp-bower'),
    composer       = require('gulp-composer'),

    seq            = require('run-sequence'),
    connect        = require('gulp-connect'),
    less           = require('gulp-less'),
    uglify         = require('gulp-uglify'),
    sourcemaps     = require('gulp-sourcemaps'),
    cssmin         = require('gulp-cssmin'),
    order          = require('gulp-order'),
    concat         = require('gulp-concat'),
    ignore         = require('gulp-ignore'),
    clean         = require('gulp-clean'),
    mobilizer      = require('gulp-mobilizer'),
    replace        = require('gulp-replace'),
    streamqueue    = require('streamqueue'),
    rename         = require('gulp-rename'),
    path           = require('path');


/*================================================
=            Report Errors to Console            =
================================================*/

gulp.on('error', function(e) {
  throw(e);
});

/*=========================================
=          Build            =
=========================================*/

gulp.task('build', function (cb) {

});


/*=========================================
=         Setup Composer Library          =
=========================================*/
// 可在install下的composer.json里配置安装的路径
// {
//     "config": {
//         "vendor-dir": "../vendor"
//     }
// }
gulp.task('composer', function (cb) {
  return composer({
    'self-install': false,
    'no-ansi'     : true,
    'working-dir' : '../install'
  });
});


/*=========================================
=          Setup Bower Library            =
=========================================*/

gulp.task('bower', function (cb) {
  return bower({
    directory: '../install/bower_components',
    cwd: '../install'
  });
});


/*=========================================
=            Clean dest folder            =
=========================================*/
gulp.task('clean', function (cb) {
  return gulp.src([
        config.clean.template_cache_dir,
        config.clean.mac_ignore_file,
        config.dest
      ], { read: false })
     .pipe(clean({ force: true }));
});

/*======================================
=            Install Sequence          =
======================================*/

gulp.task('install', function(done) {
  var tasks = ['bower'];
  seq('composer', tasks, done);
});


/*====================================
=            Default Task            =
====================================*/
gulp.task('default', ['clean'], function(done){
  var tasks = [];
  tasks.push('build');
  seq('install', tasks, done);
});
