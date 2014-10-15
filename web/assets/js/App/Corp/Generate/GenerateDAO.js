define(function (require) {
    "use strict";

    var Context          = require('Corp/Context/Context'),
        ConflictHydrator = require('Corp/Conflict/ConflictHydrator'),
        ContextHydrator  = require('Corp/Context/ContextHydrator'),
        FileDataObject   = require('Corp/File/FileDataObject'),
        _                = {};

    /*
     * @class
     * @param {Object} $http
     * @param {Object} $q
     * @param {Corp/Conflict/ConflictHydrator} conflictHydrator
     * @param {Corp/Context/ContextHydrator} contextHydrator
     */
    function GenerateDAO($http, $q, conflictHydrator, contextHydrator) {
        if ((conflictHydrator instanceof ConflictHydrator) === false) {
            throw new Error(
                'conflictHydrator must be instance of ConflictHydrator "' + conflictHydrator.constructor.name + '" given'
            );
        }
        if ((contextHydrator instanceof ContextHydrator) === false) {
            throw new Error(
                'contextHydrator must be instance of ContextHydrator "' + contextHydrator.constructor.name + '" given'
            );
        }

        _.$http            = $http;
        _.$q               = $q;
        _.conflictHydrator = conflictHydrator;
        _.contextHydrator  = contextHydrator;

        _.$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    }

    /*
     * Process the generation
     * 
     * @name process
     * @desc process the generation
     * @param {Context} context - The context
     * @param {bool} metadataNoCache - boolean
     * 
     * @return promise
     */
    GenerateDAO.prototype.process = function (context, metadataNoCache) {
        if (metadataNoCache === undefined) {
            metadataNoCache = false;
        }

        if ((context instanceof Context) === false) {
            throw new Error("Context must be instance of Context");
        }

        if (typeof(metadataNoCache) !== "boolean") {
            throw new Error("metadata_nocache must be a boolean");
        }

        var deferred = _.$q.defer(),
            datas =  $.param({
            backend            : context.getBackend(),
            metadata_nocache   : metadataNoCache,
            metadata           : context.getMetadata(),
            generator          : context.getGenerator(),
            generate           : true
        }) + '&' + $.param(context.getQuestion());

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            method  : "POST",
            url     : __BASEPATH__ + "generator",
            data    : datas,
        }).success(function (data) {
            deferred.resolve(_.contextHydrator.hydrate(data, new Context()));
        }).error(function(data) {
            deferred.reject(data.error);
        });

        return deferred.promise;
    };

    /*
     * Generate files
     * @param {Corp/Context/Context} context
     * @return {promise}
     */
    GenerateDAO.prototype.generate = function (context) {
        if ((context instanceof Context) === false) {
            throw new Error("Context must be instance of Context");
        }

        var deferred = _.$q.defer(),
            datas    = $.param({
            backend        : context.getBackend(),
            metadata       : context.getMetadata(),
            generator      : context.getGenerator(),
            questions      : context.getQuestion(),
            conflict       : $('.conflict_handle').serialize(),
            generate       : true,
            generate_files : true
        });

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : "POST",
            url     : __BASEPATH__ + "generator",
            data  : datas
        }).success(function (datas) {
            var log = null, conflictList = null;

            if (datas.conflict !== undefined) {
                conflictList = _.conflictHydrator.hydrate(datas.conflict);
            } else {
                log = datas.generationLog;
            }

            deferred.resolve({'log' : log, 'conflictList' : conflictList});
        }).error(function (data) {
            deferred.reject({'error' : data.error});
        });

        return deferred.promise;
    };

    /*
     * @param {Corp/Context/Context} context 
     * @param {Corp/File/FileDataObject} file
     * @return {promise}
     */
    GenerateDAO.prototype.viewFile = function (context, file) {

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
            deferred = _.$q.defer();

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : "POST",
            url     : __BASEPATH__ + "generator",
            data    : datas
        }).success(function (data) {
            deferred.resolve(data);
        }).error(function (data) {
            deferred.reject(data);
        });

        return deferred.promise;
    };

    return GenerateDAO;
});