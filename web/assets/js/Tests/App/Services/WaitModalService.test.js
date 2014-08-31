require(['Services/WaitModalService'], function(WaitModalService) {
    describe('Testing WaitModalService', function() {
        var service, $httpBackend;

        /*console.log('here4');
        //you need to indicate your module in a test
        beforeEach(angular.mock.module('GeneratorApp'));
        console.log('here4.5');
        beforeEach(angular.mock.inject(function($injector) {
            // Set up the mock http service responses
            $httpBackend = $injector.get('$httpBackend');
            service      = $injector.get('WaitModalService');
            console.log(service);
        }));*/
        
        /*it('should show wait modal', function() {

            $httpBackend.whenGET("assets/js/App/Template/WaitModal.html").respond('<toto></toto>');
            service.show();
            $httpBackend.flush();
            console.log('im in');
        });*/

        //it('should hide wait modal', function() {
        	WaitModalService.hide();
        	console.log('here');
        //});

        /*afterEach(function() {
            $httpBackend.verifyNoOutstandingExpectation();
        });*/
    });
});