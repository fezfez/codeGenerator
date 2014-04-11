console.log('out');
require([], function() {
	console.log('here');
    describe('Testing a service', function() {

        var service, $httpBackend;

        //you need to indicate your module in a test
        //beforeEach(module('GeneratorApp'));
        beforeEach(angular.mock.module('GeneratorApp'));
        beforeEach(angular.mock.inject(function(_$httpBackend_, ViewFileService) {
        	console.log($httpBackend);
            service      = ViewFileService;
            $httpBackend = _$httpBackend_;
        }));

        it('should invoke service with right paramaeters', function() {

            service.generate({'test' : 'test'});
            $httpBackend.flush();
        });

        afterEach(function() {
            $httpBackend.verifyNoOutstandingExpectation();
            $httpBackend.verifyNoOutstandingRequest();
        });
    });
});