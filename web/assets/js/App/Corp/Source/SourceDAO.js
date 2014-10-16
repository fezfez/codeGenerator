define(function (require) {
    'use strict';

    var SourceHydrator = require('Corp/Source/SourceHydrator'),
        _              = {};

    /*
     * @class
     * @param {Object} $http
     * @param {Object} $q
     * @param {Corp/History/HistoryHydrator} historyHydrator
     */
    function SourceDAO($http, $q) {
        _.$http          = $http;
        _.$q             = $q;
        _.sourceHydrator = new SourceHydrator();

        _.$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    }

    /*
     * Generate files
     * @param {Corp/Context/Context} context
     * @return {promise}
     */
    SourceDAO.prototype.config = function(data) {
        data.create_metadatasource = 1;
        var stringifiedParams = $.param(data),
            deferred          = _.$q.defer();

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            method  : 'POST',
            url     : __BASEPATH__ + 'generator',
            data    : stringifiedParams
        }).success(function (data) {
           deferred.resolve(_.sourceHydrator.hydrate(data));
        }).error(function (data) {
            deferred.reject(data);
        });

        return deferred.promise;
    };

    return SourceDAO;
});