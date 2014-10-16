define(function(require) {
    'use strict';

    var angular           = require('Angular'),
        Context           = require('Corp/Context/Context'),
        GenerateDAO       = require('Corp/Generate/GenerateDAO'),
        ConflictHydrator  = require('Corp/Conflict/ConflictHydrator'),
        ContextHydrator   = require('Corp/Context/ContextHydrator');

    require('AngularMock');

    describe('Testing Corp/Generate/GenerateDAO method process', function() {

        var httpBackend, q, generatorHydrator, contextHydrator, generateDAO;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($injector) {
                httpBackend       = $injector.get('$httpBackend');
                q                 = $injector.get('$q');
                generatorHydrator = new ConflictHydrator(); 
                contextHydrator   = new ContextHydrator(); 
                generateDAO = new GenerateDAO($injector.get('$http'), q, generatorHydrator, contextHydrator);
            });
        }));

        it('Should throw exception on wrong context type', function() {
            expect(function() {
                generateDAO.process('im wrong', false).then(function(context) {}, function(error) {});
            }).toThrow();
        });

        it('Should set metadatanocache to true if undefined', function() {
            httpBackend.expectPOST('generator').respond(200, '');
            generateDAO.process(new Context(), undefined).then(function(context) {}, function(error) {});
        });

        it('Should throw exception on wrong metadata_nocache type', function() {
            expect(function() {
                generateDAO.process(new Context(), 'im wrong').then(function(context) {}, function(error) {});
            }).toThrow();
        });

        it('should preview generation', function() {

            httpBackend.whenPOST('generator').respond(
                {
                    'generator' : {
                        'files' : [
                            {
                                'fileName' : 'test'
                            }
                        ],
                        'questions' : {}
                    }
                }
            );

            generateDAO.process(new Context(), false).then(
                function(directories) {
                    expect(directories instanceof Context).toBe(true);
                }, function(error) {
                    
                }
            );
            httpBackend.flush();
        });

        it('Should return error', function() {

            var errorString = 'MyError !';
            httpBackend.expectPOST('generator').respond(500, {error : errorString});

            generateDAO.process(new Context(), false).then(
                function(directories) {
                    expect(directories instanceof Context).toBe(true);
                }, function(error) {
                    expect(error).toBe(errorString);
                }
            );
            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});