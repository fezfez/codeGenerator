define(["App/App", "Corp/Context/Context", "Corp/Context/ContextHydrator"], function(app, Context, ContextHydrator) {
    "use strict";

    var Service = app.service('HistoryService', ['$http', function ($http) {
        /*
         * History
         * @param callback callable
         * @param callbackError callable
         */
        this.generate = function (history, callback, callbackError) {

            var datas =  $.param({
                'select_history' : true
            }) + '&' + $.param(history);

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
    }]);

    return Service;
});