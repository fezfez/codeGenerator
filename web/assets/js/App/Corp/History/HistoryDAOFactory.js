define(function (require) {
    'use strict';

    var app             = require('App/App'),
        HistoryHydrator = require('Corp/History/HistoryHydrator'),
        HistoryDAO      = require('Corp/History/HistoryDAO'),
        instance        = null;

    function HistoryDAOFactory($http, $q) {
        return {
            /*
             * @return {Corp/History/HistoryDAO}
             */
            getInstance : function () {
                if (instance === null) {
                    instance = new HistoryDAO($http, $q, new HistoryHydrator());
                }

                return instance;
            }
        };
    }

    return app.factory('HistoryDAOFactory', ['$http', '$q', HistoryDAOFactory]);
});