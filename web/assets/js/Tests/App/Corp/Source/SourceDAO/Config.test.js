define(function(require) {
    //'use strict';

    var angular           = require('Angular'),
        Context           = require('Corp/Context/Context'),
        SourceDAO         = require('Corp/Source/SourceDAO');

    require('AngularMock');

    describe('Testing Corp/Source/SourceDAO/Config.test', function() {

        var httpBackend = null, q = null, sourceDAO = null;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($injector) {
                // Set up the mock http service responses
                httpBackend = $injector.get('$httpBackend');
                q           = $injector.get('$q');

                sourceDAO = new SourceDAO($injector.get('$http'), q);
            });
        }));

        it('Should source in callback', function() {

            httpBackend.whenPOST('generator').respond({});

            sourceDAO.config(
                {},
                (function(data) {
                    expect(data instanceof Context).toEqual(true);
                }),
                function() {
                
                }
            );

            httpBackend.flush();
        });
        
        it('Should return error in callback', function() {

            httpBackend.expectPOST('generator').respond(500, '');

            sourceDAO.config(
                {},
                (function(data) {
                    
                }),
                function(data) {
                    expect(typeof(data) === 'string').toEqual(true);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});