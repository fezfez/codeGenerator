define(function (require) {
    "use strict";

    var app              = require('App/App'),
        ConflictHydrator = require('Corp/Conflict/ConflictHydrator'),
        GenerateDAO      = require('Corp/Generate/GenerateDAO'),
        instance         = null;

    function GenerateDAOFactory($http, $q) {
        return {
            /*
             * @return {Corp/Generate/GenerateDAO}
             */
            getInstance : function () {
                if (instance === null) {
                    instance = new GenerateDAO($http, $q, new ConflictHydrator());
                }

                return instance;
            }
        };
    }

    return app.factory('GenerateDAOFactory', ['$http', '$q', GenerateDAOFactory]);
});