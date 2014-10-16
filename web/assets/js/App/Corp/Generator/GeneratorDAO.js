define(function(require) {
    'use strict';

    var GeneratorHydrator = require('Corp/Generator/GeneratorHydrator'),
        GeneratorDto      = require('Corp/Generator/GeneratorDto'),
        _                 = {};

    /**
     * @module GeneratorService
     */
    function GeneratorDAO($http, $q, $sce, generatorHydrator) {

    	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        _.$http             = $http;
        _.$q                = $q;
        _.$sce              = $sce;
        _.generatorHydrator = generatorHydrator;
    }

    /*
     * download a genrator
     * @param callback callable
     * @param callbackError callable
     */
    GeneratorDAO.prototype.download = function (generator, callback, callbackError) {

        if ((generator instanceof GeneratorDto) === false) {
            throw new Error('generator must be instance of GeneratorDto');
        }

        var datas =  $.param({
            'search_generator'           : true,
            'install_new_package'        : true,
            'stream'                     : true,
            'search_generatorcollection' : generator.getLabel()
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
     * Search generator by name
     * 
     * @param generatorName string
     * 
     * @return promise
     */
    GeneratorDAO.prototype.searchByName = function (generatorName) {

        var deferred = _.$q.defer(),
            datas    = $.param({
            'search_generator' : true,
            'generator_name' : generatorName
        });

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
            method  : 'POST',
            url     : __BASEPATH__ + 'generator',
            data    : datas,
        }).success(function (data) {
            deferred.resolve(_.generatorHydrator.hydrateCollection(data));
        }).error(function(data) {
            deferred.reject(data.error);
        });

        return deferred.promise;
    };

    GeneratorDAO.prototype.findOneByName = function (generator) {

        if (generator.constructor.name !== 'GeneratorDto') {
            throw new Error('generator must be instance of GeneratorDto "' + generator.constructor.name + '" given');
        }

        var datas =  $.param({
            'search_generator'           : true, 
            'package_detail'             : true,
            'search_generatorcollection' : generator.getLabel()
        }), deferred = _.$q.defer();

        _.$http({
            headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            method  : 'POST',
            url     : __BASEPATH__ + 'generator',
            data    : datas
        }).success(function (data) {
            deferred.resolve(_.generatorHydrator.hydrate(generator, data));
        }).error(function(data) {
            deferred.reject({'error' : data.error});
        });

        return deferred.promise;
    };

    return GeneratorDAO;
});