define(["App/App", "Corp/Context/Context", "Corp/Context/ContextHydrator"], function(app, Context, ContextHydrator) {
    "use strict";

    var Service = app.service('SearchGeneratorService', ['$http', function ($http) {
        /*
         * Search generator by name
         * 
         * @param callback callable
         * @param callbackError callable
         */
        this.generate = function (history, callback, callbackError) {

            var datas =  $.param({
                'search_generator' : true
            }) + '&' + $.param({'generator_name' : history});

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method: "POST",
                    url: __BASEPATH__ + "generator",
                    data: datas
                }
            ).success(function (data) {
                var contextHydrator = new ContextHydrator();

                callback(contextHydrator.hydrate(data, new Context()));
            }).error(function(data) {
                callbackError({'error' : data.error});
            });
        };

        this.detail = function (history, callback, callbackError) {

            var datas =  $.param({
                'search_generator' : true,
                'package_detail' : true,
                'search_generatorcollection' : history
            });

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method: "POST",
                    url: __BASEPATH__ + "generator",
                    data: datas
                }
            ).success(function (data) {
                callback(data);
            }).error(function(data) {
                callbackError({'error' : data.error});
            });
        };
    }]);

    return Service;
});