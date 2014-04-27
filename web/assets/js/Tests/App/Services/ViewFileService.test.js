require(['Services/ViewFileService'], function(ViewFileService) {
    describe('Testing viewFileService', function() {

        var service, $httpBackend;

        //you need to indicate your module in a test
        beforeEach(module('GeneratorApp'));
        beforeEach(inject(function($injector) {
            // Set up the mock http service responses
            $httpBackend = $injector.get('$httpBackend');
            service      = $injector.get('ViewFileService');
        }));
        
        it('should preview file', function() {

            $httpBackend.whenPOST("view-file").respond([{
                id: 1,
                name: "banana"
              }]);
            
            service.generate({'test' : 'test'}, (function(data) {

            }));
            $httpBackend.flush();
        });

        afterEach(function() {
            $httpBackend.verifyNoOutstandingExpectation();
            //$httpBackend.verifyNoOutstandingRequest();
        });
    });
});