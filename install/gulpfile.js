'use strict';

/*=====================================
=        Default Configuration        =
=====================================*/
var config = {
  dest   : '../bin',
  ueditor: {
    src  : 'bower_components/ueditor/',
    dist : 'bower_components/ueditor/dist/utf8-php',
    dest : '../misc/js/onlineditor/'
  },
  clean  : {
    template_cache_dir: '../home/**/tmp/templates_c/**',
    mac_ignore_file   : '../**/.DS_Store'
  }
};
/*-----  End of Configuration  ------*/

/*========================================
=            Requiring stuffs            =
========================================*/

var gulp   = require('gulp'),
    run    = require('gulp-run'),
    bower  = require('gulp-bower'),
    seq    = require('run-sequence'),
    ignore = require('gulp-ignore'),
    clean  = require('gulp-clean'),
    rename = require('gulp-rename'),
    path   = require('path');

/*=========================================
=          Setup Bower Library            =
=========================================*/

gulp.task('bower', function (cb) {
  return bower();
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
=            Install ueditor           =
======================================*/

gulp.task('ueditor_cp', ['ueditor_bak'], function() {
  // console.log('ueditor work!');
  // 复制粘贴 install/bower_components/ueditor/dist -> misc/js/onlineditor/ueditor 目录下
  gulp.src(config.ueditor.dist + '/**/*')
      .pipe(gulp.dest(path.join(config.ueditor.dest, "ueditor")));
});

gulp.task('ueditor', function() {
  // console.log('ueditor_end work!');
  // 复制粘贴 misc/js/onlineditor/ueditor_bak -> misc/js/onlineditor/ueditor 即可.
  gulp.src(config.ueditor.dest + '/ueditor_bak/**/*')
      .pipe(gulp.dest(path.join(config.ueditor.dest, "ueditor")));
});

gulp.task('ueditor_bak', function() {
  // console.log('ueditor_bak work!');
  // // 先备份misc/js/onlineditor/ueditor 目录下的文件 到 misc/js/onlineditor/ueditor_bak 下
  // gulp.src(config.ueditor.dest + '/ueditor/**/*')
  //     .pipe(gulp.dest(path.join(config.ueditor.dest, 'ueditor_bak')));
});

//编译ueditor库
gulp.task('ueditor_grunt', function() {
  // console.log('ueditor_grunt work!');
  new run.Command('sudo npm install', {
    cwd: config.ueditor.src
  }).exec();

  new run.Command('sudo npm install -g grunt', {
    cwd: config.ueditor.src
  }).exec();

  new run.Command('sudo grunt default', {
    cwd: config.ueditor.src
  }).exec();

});

/*======================================
=           Install Js Library         =
======================================*/

gulp.task('install', function(done) {
  var tasks = ['bower'];
  seq(tasks, done);
});


/*=========================================
=          Build            =
=========================================*/

gulp.task('build', function (done) {
  seq('ueditor_grunt', done);
});

/*====================================
=            Default Task            =
====================================*/
gulp.task('default', function(done){ // ['clean'],
  var tasks = [];
  tasks.push('build');
  seq('install', tasks, done);
});
