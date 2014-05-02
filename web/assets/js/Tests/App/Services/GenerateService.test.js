require(['Services/GenerateService'], function(GeneratorService) {
    describe('Testing generateService', function() {

        var service, $httpBackend;

        //you need to indicate your module in a test
        beforeEach(module('GeneratorApp'));
        beforeEach(inject(function($injector) {
            // Set up the mock http service responses
            $httpBackend = $injector.get('$httpBackend');
            service      = $injector.get('GenerateService');
        }));
        
        it('should generate', function() {

            $httpBackend.whenPOST("generate").respond(
                {
                    'generationLog' : {}
                }
            );

            service.generate(
                {
                
                },
                function(response) {

                }
            );
            $httpBackend.flush();
        });

        afterEach(function() {
            $httpBackend.verifyNoOutstandingExpectation();
            //$httpBackend.verifyNoOutstandingRequest();
        });
    });
});