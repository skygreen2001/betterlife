'use strict';
/**
 * 使用 Gulp 工具,这里可以重置install/gulpfile.js里的config变量配置
 */
module.exports = function(config) {

  /**
   * The output directory.
   *
   * @property config.dest
   * @type {String}
   */
  config.dest = 'bin';

  //
  // Development web server
  //

  /**
   * Development server config.
   *
   * @type {Boolean}
   * @property config.server
   *
   * @example Disable development server
   *   config.server = false;
   */
  config.server = false;

  /**
   * The host name where to bind development server.
   *
   * @property config.server.host
   * @type {String}
   */
  // config.server.host = '0.0.0.0';

  /**
   * The port where development server will to listen.
   *
   * @property config.server.port
   * @type {String}
   */
  // config.server.port = '8000';

};
