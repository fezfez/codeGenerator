define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Services/GeneratorService'],
    function(angular, mock, Context)
{
    describe('Testing GeneratorService, SearchByName method', function() {

        var httpBackend = _GeneratorService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(GeneratorService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend     = $httpBackend;
                _GeneratorService_ = GeneratorService;
            });
        }));

        it('Should return caallback with response', function() {

            httpBackend.whenPOST("generator").respond({});

            _GeneratorService_.searchByName('test').then(
                (function(data) {
                    expect(data instanceof Context).toEqual(true);
                }),
                function() {
                
                }
            );

            httpBackend.flush();
        });
        
        it('Should return error callback', function() {

            var errorValue = 'my Error !';
            httpBackend.expectPOST('generator').respond(500, {error : errorValue});

            _GeneratorService_.searchByName('test').then(
                (function(data) {
                    
                }),
                function(data) {
                    expect(data.error).toEqual(errorValue);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});