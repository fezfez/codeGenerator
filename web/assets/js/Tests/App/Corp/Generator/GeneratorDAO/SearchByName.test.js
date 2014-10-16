define(function(require) {
    'use strict';

    var angular                = require('Angular'),
        GeneratorDAO           = require('Corp/Generator/GeneratorDAO'),
        GeneratorHydrator      = require('Corp/Generator/GeneratorHydrator'),
        GeneratorCollectionDTO = require('Corp/Generator/GeneratorCollectionDTO'),
        ContextHydrator        = require('Corp/Context/ContextHydrator');

    require('AngularMock');

    describe('Testing Corp/Generator/GeneratorDAO method searchByName', function() {

        var httpBackend, generatorDAO, q, sce, generatorHydrator;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($injector) {

                httpBackend       = $injector.get('$httpBackend');
                q                 = $injector.get('$q');
                sce               = $injector.get('$sce');
                generatorHydrator = new GeneratorHydrator(sce, new ContextHydrator());

                generatorDAO = new GeneratorDAO($injector.get('$http'), q, sce, generatorHydrator);
            });
        }));

        it('Should return promise with response', function() {

            httpBackend.whenPOST('generator').respond({});

            generatorDAO.searchByName('test').then(
                function(data) {
                    expect(data instanceof GeneratorCollectionDTO).toEqual(true);
                },
                function() {
                
                }
            );

            httpBackend.flush();
        });

        it('Should return error promise', function() {

            var errorValue = 'my Error !';
            httpBackend.expectPOST('generator').respond(500, {error : errorValue});

            generatorDAO.searchByName('test').then(
                function(data) {
                    
                },
                function(error) {
                    expect(error).toEqual(errorValue);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});
