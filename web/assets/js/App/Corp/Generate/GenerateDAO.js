define(function (require) {
    "use strict";

    var Context          = require('Corp/Context/Context'),
        ConflictHydrator = require('Corp/Conflict/ConflictHydrator'),
        FileDataObject   = require('Corp/File/FileDataObject'),
        _                = {};

    /*
     * @class
     * @param {Object} $http
     * @param {Object} $q
     * @param {Corp/Conflict/ConflictHydrator} conflictHydrator
     */
    function GenerateDAO($http, $q, conflictHydrator) {
        if ((conflictHydrator instanceof ConflictHydrator) === false) {
            throw new Error("conflictHydrator must be instance of ConflictHydrator");
        }

        _.$http            = $http;
        _.$q               = $q;
        _.conflictHydrator = conflictHydrator;

        _.$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    }

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
            datas    = {
            backend        : context.getBackend(),
            metadata       : context.getMetadata(),
            generator      : context.getGenerator(),
            questions      : context.getQuestion(),
            conflict       : $('.conflict_handle').serialize(),
            generate       : true,
            generate_files : true
        };

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : "POST",
            url     : __BASEPATH__ + "generator",
            params  : datas
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
    };

    return GenerateDAO;
});