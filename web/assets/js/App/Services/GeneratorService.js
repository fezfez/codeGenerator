define(["App/App", "Corp/Context/Context", "Corp/Context/ContextHydrator"], function(app, Context, ContextHydrator) {
    "use strict";

    /**
     * @module GeneratorService
     */
    function GeneratorService($http, $q, $sce) {

        $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        var httpQueryAndHydrateResponse = function(datas) {
            var deferred = $q.defer();

            $http({
                headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                method  : "POST",
                url     : __BASEPATH__ + "generator",
                data    : datas,
            }).success(function (data) {
                var contextHydrator = new ContextHydrator();

                deferred.resolve(contextHydrator.hydrate(data, new Context()));
            }).error(function(data) {
                deferred.reject(data.error);
            });

            return deferred.promise;
        };

        /*
         * download a genrator
         * @param callback callable
         * @param callbackError callable
         */
        this.download = function (history, callback, callbackError) {

            var datas =  $.param({
                'search_generator' : true,
                'install_new_package' : true,
                'stream'  : true,
                'search_generatorcollection' : history
            }),
                download = {
                    log : [],
                    end : false
                };

            var stream = new EventSource(__BASEPATH__ + 'generator?' + datas);

            stream.addEventListener('message', function (event) {
                download.log.push($sce.trustAsHtml(event.data));

                callback(download);
            });

            stream.addEventListener('end', function (event) {
                stream.close();
                download.log.push('Installation finished');
                download.end = true;
                callback(download);
            });
        };

        /*
         * Process the generator
         * 
         * @name process
         * @desc process the generation
         * @param {Context} context - The context
         * @param {bool} metadataNoCache - boolean
         * 
         * @return promise
         */
        this.process = function (context, metadataNoCache) {
            if (metadataNoCache === undefined) {
                metadataNoCache = false;
            }

            if ((context instanceof Context) === false) {
                throw new Error("Context must be instance of Context");
            }

            if (typeof(metadataNoCache) !== "boolean") {
                throw new Error("metadata_nocache must be a boolean");
            }

            var datas =  $.param({
                backend            : context.getBackend(),
                metadata_nocache   : metadataNoCache,
                metadata           : context.getMetadata(),
                generator          : context.getGenerator(),
                generate           : true
            }) + '&' + $.param(context.getQuestion());

            return httpQueryAndHydrateResponse(datas);
        };

        /*
         * Search generator by name
         * 
         * @param generatorName string
         * 
         * @return promise
         */
        this.searchByName = function (generatorName) {

            var datas =  $.param({
                'search_generator' : true,
                'generator_name' : generatorName
            });

            return httpQueryAndHydrateResponse(datas);
        };

        this.findOneByName = function (name) {

            var datas =  $.param({
                'search_generator'           : true,
                'package_detail'             : true,
                'search_generatorcollection' : name
            }), deferred = $q.defer();

            $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                method: "POST",
                url: __BASEPATH__ + "generator",
                data: datas
            }).success(function (data) {
                deferred.resolve({
                    'readme' : $sce.trustAsHtml(data.package_details.readme),
                    'github' : data.package_details.github
                });
            }).error(function(data) {
                deferred.reject({'error' : data.error});
            });
        };
    }

    return app.service('GeneratorService', ['$http', '$q', '$sce', GeneratorService]);
});