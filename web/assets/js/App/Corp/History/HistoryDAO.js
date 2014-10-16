define(function (require) {
    'use strict';

    var HistoryHydrator = require('Corp/History/HistoryHydrator'),
        _               = {};

    /*
     * @class
     * @param {Object} $http
     * @param {Object} $q
     * @param {Corp/History/HistoryHydrator} historyHydrator
     */
    function HistoryDAO($http, $q, historyHydrator) {
        if ((historyHydrator instanceof HistoryHydrator) === false) {
            throw new Error('historyHydrator must be instance of HistoryHydrator');
        }

        _.$http           = $http;
        _.$q              = $q;
        _.historyHydrator = historyHydrator;

        _.$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    }

    function defferedHistory(datas) {
        var deferred = _.$q.defer();

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : 'POST',
            url     : __BASEPATH__ + 'generator',
            data    : datas
        }).success(function (data) {
            deferred.resolve(_.historyHydrator.hydrateCollection(data));
        }).error(function(data) {
            deferred.reject({'error' : data.error});
        });

        return deferred.promise;
    };

    /*
     * Generate files
     * @param {Corp/Context/Context} context
     * @return {promise}
     */
    HistoryDAO.prototype.generate = function (history) {

        var datas = $.param({
            'select_history' : true
        }) + '&' + $.param(history);

        return defferedHistory(datas);
    };

    /*
     * Retrieve all
     * @param {Corp/Context/Context} context
     * @return {promise}
     */
    HistoryDAO.prototype.retrieveAll = function () {
        return defferedHistory($.param({'select_history' : true}));
    };

    return HistoryDAO;
});