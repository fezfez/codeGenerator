GeneratorApp.factory('ViewFileService', ['$http', function ($http) {
	"use strict";

    return {
        generate : function (datas, callback) {
        	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method: "POST",
                    url: "view-file",
                    data: datas
                }
            ).success(function (data) {
                callback(data);
            });
        }
    };
}]);