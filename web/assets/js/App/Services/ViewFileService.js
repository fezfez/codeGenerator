define(['App/App'], function(app) {
    "use strict";

    var Service = app.service('ViewFileService', ['$http', function ($http) {
        /*
         * @param datas Array
         * @param callback callable
         */
        this.generate = function(datas, callback)
        {
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method  : "POST",
                    url     : __BASEPATH__ + "view-file",
                    data    : datas
                }
            ).success(function (data) {
                callback(data);
            }).error(function(data) {
                callback(data);
            });
        };
    }]);

    return Service;
});