define(function(require) {
    "use strict";
    var app             = require('App/App'),
        Context         = require('Corp/Context/Context'),
        ContextHydrator = require('Corp/Generator/GeneratorHydrator'),
        _               = {};
    /**
     * @module GeneratorService
     */
    function GeneratorService($http, $q, $sce) {
    	
    	_.$http = $http;
    	_.$q    = $q;
    	_.$sce  = $sce;
    	_.contextHydrator = new ContextHydrator();

    	_.$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    }
    
    var httpQueryAndHydrateResponse = function(datas) {
        var deferred = _.$q.defer();

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
     * download a genrator
     * @param callback callable
     * @param callbackError callable
     */
    GeneratorService.prototype.download = function (history, callback, callbackError) {

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
    GeneratorService.prototype.process = function (context, metadataNoCache) {
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
    GeneratorService.prototype.searchByName = function (generatorName) {

        var datas =  $.param({
            'search_generator' : true,
            'generator_name' : generatorName
        });

        return httpQueryAndHydrateResponse(datas);
    };

    GeneratorService.prototype.findOneByName = function (generator) {

        var datas =  $.param({
            'search_generator'           : true, 
            'package_detail'             : true,
            'search_generatorcollection' : generator.getLabel()
        }), deferred = $q.defer();

        $http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : "POST",
            url     : __BASEPATH__ + "generator",
            data    : datas
        }).success(function (data) {
            deferred.resolve({
                'readme' : $sce.trustAsHtml(data.package_details.readme),
                'github' : data.package_details.github
            });
        }).error(function(data) {
            deferred.reject({'error' : data.error});
        });

        return deferred;
    };

    return app.service('GeneratorService', ['$http', '$q', '$sce', GeneratorService]);
});