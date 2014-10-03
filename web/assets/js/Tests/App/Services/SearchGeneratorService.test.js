define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Services/SearchGeneratorService'],
    function(angular, mock, Context)
{
    describe('Testing SearchGeneratorService', function() {

        var httpBackend = _SearchGeneratorService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(SearchGeneratorService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend     = $httpBackend;
                _SearchGeneratorService_ = SearchGeneratorService;
            });
        }));

        it('Should return caallback with response', function() {

            httpBackend.whenPOST("generator").respond({});

            _SearchGeneratorService_.generate(
                'test',
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

            _SearchGeneratorService_.generate(
                'test',
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