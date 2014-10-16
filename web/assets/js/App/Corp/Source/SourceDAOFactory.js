define(function (require) {
    'use strict';

    var app       = require('App/App'),
        SourceDAO = require('Corp/Source/SourceDAO'),
        instance  = null;

    function SourceDAOFactory($http, $q) {
        return {
            /*
             * @return {Corp/Source/SourceDAO}
             */
            getInstance : function () {
                if (instance === null) {
                    instance = new SourceDAO($http, $q);
                }

                return instance;
            }
        };
    }

    return app.factory('SourceDAOFactory', ['$http', '$q', SourceDAOFactory]);
});