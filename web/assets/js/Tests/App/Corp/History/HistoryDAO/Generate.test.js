define(function(require) {
    'use strict';

    var angular              = require('Angular'),
        HistoryDAO           = require('Corp/History/HistoryDAO'),
        HistoryHydrator      = require('Corp/History/HistoryHydrator'),
        HistoryCollectionDTO = require('Corp/History/HistoryCollectionDTO');

    require('AngularMock');

    describe('Testing Corp/History/HistoryDAO method generate', function() {

        var httpBackend, q, historyHydrator, historyDAO;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($injector) {
                // Set up the mock http service responses
                httpBackend     = $injector.get('$httpBackend');
                q               = $injector.get('$q');
                historyHydrator = new HistoryHydrator(); 
                historyDAO      = new HistoryDAO($injector.get('$http'), q, historyHydrator);
            });
        }));

        it('Should do something...', function() {
            httpBackend.whenPOST('generator').respond({});
            httpBackend.expectPOST('generator');

            historyDAO.generate('im wrong').then(function(result) {
                expect(result instanceof HistoryCollectionDTO).toBe(true);
            });

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});