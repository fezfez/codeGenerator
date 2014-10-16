define(['Angular', 'AngularMock', 'Services/WaitModalService'], function(angular, mock) {
    "use strict";

    describe('Testing WaitModalService', function() {

        var httpBackend = null, service = null;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(WaitModalService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend = $httpBackend;
                service     = WaitModalService;
            });
        }));

        it('should show wait modal', function () {
            httpBackend.whenGET("assets/js/App/Template/WaitModal.html").respond('<toto></toto>');
            service.show();
            httpBackend.flush();
        });

        it('should hide wait modal', function() {
            service.hide();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});