require(['Services/GeneratorService', "Corp/Directory/DirectoryDataObject"], function(GeneratorService, DirectoryDataObject) {
    describe('Testing a service', function() {

        var service, $httpBackend;

        //you need to indicate your module in a test
        beforeEach(module('GeneratorApp'));
        beforeEach(inject(function($injector) {
            // Set up the mock http service responses
            $httpBackend = $injector.get('$httpBackend');
            $q           = $injector.get('$q');
            service      = $injector.get('GeneratorService');
        }));
        
        it('should preview generation', function() {

            $httpBackend.whenPOST("generator").respond(
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

            service.build(
                {
                
                },
                function(directories, questions) {
                    assert.instanceOf(directories, DirectoryDataObject, 'directories is an instance of DirectoryDataObject');
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