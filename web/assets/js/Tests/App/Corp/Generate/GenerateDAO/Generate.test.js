define(function(require) {
    'use strict';

    var angular           = require('Angular'),
        Context           = require('Corp/Context/Context'),
        GenerateDAO       = require('Corp/Generate/GenerateDAO'),
        ConflictHydrator  = require('Corp/Conflict/ConflictHydrator'),
        ContextHydrator   = require('Corp/Context/ContextHydrator');

    require('AngularMock');

    describe('Testing Corp/Generator/GenerateDAO method generate', function() {

        var httpBackend, q, generatorHydrator, contextHydrator, generateDAO;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($injector) {
                // Set up the mock http service responses
                httpBackend       = $injector.get('$httpBackend');
                q                 = $injector.get('$q');
                generatorHydrator = new ConflictHydrator(); 
                contextHydrator   = new ContextHydrator(); 
                generateDAO       = new GenerateDAO($injector.get('$http'), q, generatorHydrator, contextHydrator);
            });
        }));

        it('Should throw error on wrong context type', function() {
            expect(function() {
                generateDAO.generate('im wrong')
            }).toThrow();
        });

        it('Should return generationLog', function() {

            var generationLog = 'im a log';
            httpBackend.whenPOST('generator').respond({'generationLog' : generationLog});
            httpBackend.expectPOST('generator');

            generateDAO.generate(new Context()).then(
                (function(data) {
                    expect(data).toEqual({ 'log' : generationLog, 'conflictList' : null});
                }),
                function() {
                
                }
            );

            httpBackend.flush();
        });
        
        it('Should call error promise', function() {

            var myError = 'i am an error';

            httpBackend.whenPOST('generator').respond(500, {error : myError});
            httpBackend.expectPOST('generator');

            generateDAO.generate(new Context()).then(
                (function(data) {
                    
                }),
                function(data) {
                    expect(data.error).toEqual(myError);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});