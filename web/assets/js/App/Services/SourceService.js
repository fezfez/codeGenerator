define(['App/App'], function (app) {
    "use strict";

    var Service = app.service('SourceService', ['$http', function ($http) {

        /*
         * Retrieve source list
         * @param callbackSuccess callable
         * @param callbackError callable
         */
        this.retrieveAdapters = function (config, callbackSuccess, callbackError) {
        	var datas =  $.param({metadatasource: config.getAdapter()});
        	
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method  : "POST",
                    data    : datas,
                    url     : __BASEPATH__ + "adapter-form",
                }
            ).success(function (data) {
                callbackSuccess(data);
            }).error(function (data) {
               callbackError(data);
            });
        };
        
        /*
         * Retrieve source list
         * @param callbackSuccess callable
         * @param callbackError callable
         */
        this.retrieveFormForAdapter = function (backend, callbackSuccess, callbackError) {
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';
            var datas =  $.param({backend: backend});
            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method  : "POST",
                    data    : datas,
                    url     : __BASEPATH__ + "adapter-form",
                }
            ).success(function (data) {
                callbackSuccess(data);
            }).error(function (data) {
               callbackError(data);
            });
        };

        /*
         * @param string source
         * @param data array
         * @param callable successCallback
         */
        this.config = function(source, data, successCallback) {
            var datas             = $.extend(data, {backend : source}),
                stringifiedParams = $.param(datas);

            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                    method  : 'POST',
                    url     : __BASEPATH__ + 'metadata-save',
                    data    : stringifiedParams
                }
            ).success(function (data) {
               successCallback(data);
            });
        };
    }]);

    return Service;
});