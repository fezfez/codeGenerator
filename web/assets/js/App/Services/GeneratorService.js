define([
    "App/App",
    "Corp/Directory/DirectoryDataObject",
    "Corp/Directory/DirectoryBuilderFactory",
    "Corp/Context/Context",
    "Corp/Context/ContextHydrator"
    ],
    function(app, DirectoryDataObject, DirectoryBuilderFactory, Context, ContextHydrator) {
    "use strict";

    var Service = app.service('GeneratorService', ['$http', '$q', function ($http, $q) {

        var http = false;

        /*
         * Generate a preview
         * @param context Corp/Context/Context
         * @param callbackAfterAjax callable
         */
        this.build = function (context, callbackAfterAjax) {
            var canceler = $q.defer();

            if ((context instanceof Context) === false) {
                throw new Error("Context must be instance of Context");
            }

            if (http === true) {
                //canceler.resolve();
            }

            http = true;

            var datas =  $.param({
                backend   : context.getBackend(),
                metadata  : context.getMetadata(),
                generator : context.getGenerator()
            }) + '&' + $.param(context.getQuestion());


            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                    method  : "POST",
                    url     : __BASEPATH__ + "generator",
                    data    : datas,
                    timeout : canceler.promise
                }
            ).success(function (data) {
                var contextHydrator = new ContextHydrator();

                callbackAfterAjax(contextHydrator.hydrate(data, new Context()));
                http = false;
            }).error(function(data) {
                alert(data.error);
            });
        };
    }]);

    return Service;
});