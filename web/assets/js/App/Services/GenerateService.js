define(["App/App", "Corp/Context/Context", "Corp/Conflict/ConflictHydrator"], function(app, Context, ConflictHydrator) {
    "use strict";

    function GenerateService($http, $q) {
        /*
         * Generate files
         * @param context Context
         * @param callback callable
         */
        this.generate = function (context, callback) {
            if ((context instanceof Context) === false) {
                throw new Error("Context must be instance of Context");
            }

            var datas =  $.param({
                backend        : context.getBackend(),
                metadata       : context.getMetadata(),
                generator      : context.getGenerator(),
                questions      : context.getQuestion(),
                conflict       : $('.conflict_handle').serialize(),
                generate       : true,
                generate_files : true
            }), deferred = $q.defer();

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                method: "POST",
                url: __BASEPATH__ + "generator",
                data: datas
            }).success(function (datas) {
                var log = null, conflictList = null;

                if (datas.conflict !== undefined) {
                    conflictList = ConflictHydrator.hydrate(datas.conflict);
                } else {
                    log = datas.generationLog;
                }

                deferred.resolve({'log' : log, 'conflictList' : conflictList});
            }).error(function(data) {
            	deferred.reject({'error' : data.error});
            });
        };

        /*
         * @param context Corp/Context/Context
         * @param file Corp/File/FileDataObject
         * @param callback callable
         */
        this.viewFile = function (context, file, callback) {

            if ((context instanceof Context) === false) {
                throw new Error("Context must be instance of Context");
            }

            if ((file instanceof FileDataObject) === false) {
                throw new Error("File must be instance of FileDataObject");
            }

            var datas =  $.param({
                backend          : context.getBackend(),
                metadata         : context.getMetadata(),
                generator        : context.getGenerator(),
                skeletonPath     : file.getSkeletonPath(),
                file_to_generate : file.getTemplate(),
                view_file        : true,
                generate         : true
            }) + '&' + $.param(context.getQuestion()),
            deferred = $q.defer();

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http({
                headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                method  : "POST",
                url     : __BASEPATH__ + "generator",
                data    : datas
            }).success(function (data) {
            	deferred.resolve(data);
            }).error(function (data) {
            	deferred.reject(data);
            });
        };
    }

    return app.service('GenerateService', ['$http', '$q', GenerateService]);
});