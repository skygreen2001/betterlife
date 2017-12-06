angular.
  module('bb.service').
  service('ServerService', ['$resource', '$location', 'Constants', 'ShareObject', '$http', '$httpParamSerializerJQLike',
    function($resource, $location, Constants, ShareObject, $http, $httpParamSerializerJQLike) {
      var api_url = "api/mobile/"

      this.server_config = function(){
        var host = $location.host();
        var port = $location.port();
        // console.log("host:"+host+";port:"+port);

        var url = Constants.SERVER_RUN;
        if (port==Constants.PORT) {
          url = Constants.SERVER_TEST;
          if (host!='127.0.0.1' && host!='localhost') url=url.replace(/127\.0\.0\.1/,host);
        }
        return url;
      };

      this.init = function(){
        Constants.SERVER_RUN = this.server_config();
        Constants.IMG_URL    = Constants.SERVER_RUN + "app/src";
      };

      // 退出登录
      this.exit = function() {
          return $http({
              method: 'GET',
              url: Constants.SERVER_RUN + api_url + 'logout',
              headers: {
                  'Content-Type': "application/json"
              },
               data: ""
          });
      };

      this.blogs = function() {
        return $http({
          method: 'GET',
          url: Constants.SERVER_RUN + api_url + 'listBlogs.php',
          headers: {
            'Content-Type': "application/json"
          },
          data: ""
        });
      };
    }
  ]);
