require(['Services/WaitModalService'], function(WaitModalService) {
    describe('Testing waitModalservice', function() {

        var service, $httpBackend;

        //you need to indicate your module in a test
        beforeEach(module('GeneratorApp'));
        beforeEach(inject(function($injector) {
            // Set up the mock http service responses
            $httpBackend = $injector.get('$httpBackend');
            service      = $injector.get('WaitModalService');
        }));
        
        it('should show wait modal', function() {

            $httpBackend.whenGET("assets/js/App/Template/WaitModal.html").respond('<toto></toto>');
            service.show();
            $httpBackend.flush();
        });

        it('should hide wait modal', function() {
        	service.hide();
        });

        afterEach(function() {
            $httpBackend.verifyNoOutstandingExpectation();
            //$httpBackend.verifyNoOutstandingRequest();
        });
    });
});