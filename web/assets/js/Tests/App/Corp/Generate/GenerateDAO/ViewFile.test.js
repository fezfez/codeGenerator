define(function(require) {
    'use strict';

    var angular           = require('Angular'),
        Context           = require('Corp/Context/Context'),
        GenerateDAO       = require('Corp/Generate/GenerateDAO'),
        ConflictHydrator  = require('Corp/Conflict/ConflictHydrator'),
        ContextHydrator   = require('Corp/Context/ContextHydrator'),
        FileDataObject    = require('Corp/File/FileDataObject');

    require('AngularMock');

    describe('Testing Corp/Generate/GenerateDAO method viewFile', function() {

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

        it('Should return the preview file in callback', function() {

            var generateData = {
                id: 1,
                name: 'banana'
            };
            httpBackend.whenPOST('generator').respond(generateData);

            generateDAO.viewFile(new Context(), new FileDataObject()).then((function(data) {
                expect(data).toEqual(generateData);
            }));

            httpBackend.flush();
        });

        it('Should return the error data', function() {


            httpBackend.whenPOST('generator').respond(500, '');
            httpBackend.expectPOST('generator');

            generateDAO.viewFile(new Context(), new FileDataObject()).then((function(data) {
                expect(data).toEqual('');
            }));

            httpBackend.flush();
        });

        it('Should throw exception on wrong context type', function() {
            expect(function() {
                generateDAO.viewFile('im wrong', new FileDataObject())
            }).toThrow();
        });

        it('Should throw exception on wrong file type', function() {
            expect(function() {
                generateDAO.viewFile(new Context(), 'im wrong')
            }).toThrow();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});