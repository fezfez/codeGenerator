define(['App/App', "Corp/Context/Context", "Corp/Context/ContextHydrator"], function (app, Context, ContextHydrator) {
    "use strict";

    var Service = app.service('SourceService', ['$http', function ($http) {

        /*
         * @param string source
         * @param data array
         * @param callable successCallback
         */
        this.config = function(data, successCallback, callbackError) {
            data.create_metadatasource = 1;
            var stringifiedParams = $.param(data);

            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                    method  : 'POST',
                    url     : __BASEPATH__ + 'generator',
                    data    : stringifiedParams
                }
            ).success(function (data) {
               var contextHydrator = new ContextHydrator();

               successCallback(contextHydrator.hydrate(data, new Context()), data);
            }).error(function (data) {
               callbackError(data);
            });
        };
    }]);

    return Service;
});