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
        this.build = function (context, metadata_nocache, callbackAfterAjax, callbackError) {
            var canceler = $q.defer();

            if ((context instanceof Context) === false) {
                throw new Error("Context must be instance of Context");
            }
            
            if (metadata_nocache === undefined) {
                metadata_nocache = false;
            }
            
            if (typeof(metadata_nocache) !== "boolean") {
                throw new Error("metadata_nocache must be a boolean");
            }
            
            if (typeof(callbackAfterAjax) !== "function") {
                throw new Error("callbackAfterAjax must be of type function");
            }
            
            if (typeof(callbackError) !== "function") {
                throw new Error("callbackError must be of type function");
            }

            if (http === true) {
                //canceler.resolve();
            }

            http = true;

            var datas =  $.param({
                backend            : context.getBackend(),
                'metadata_nocache' : metadata_nocache,
                metadata           : context.getMetadata(),
                generator          : context.getGenerator(),
                generate           : true
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
                callbackError(data.error);
            });
        };
    }]);

    return Service;
});