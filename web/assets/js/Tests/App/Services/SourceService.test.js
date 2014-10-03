define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Services/SourceService'],
    function(angular, mock, Context)
{
    describe('Testing SourceService', function() {

        var httpBackend = undefined, _SourceService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(SourceService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend     = $httpBackend;
                _SourceService_ = SourceService;
            });
        }));

        it('Should source in callback', function() {

            httpBackend.whenPOST("generator").respond({});

            _SourceService_.config(
                {},
                (function(data) {
                    expect(data instanceof Context).toEqual(true);
                }),
                function() {
                
                }
            );

            httpBackend.flush();
        });
        
        it('Should return error in callback', function() {

            httpBackend.expectPOST('generator').respond(500, '');

            _SourceService_.config(
                {},
                (function(data) {
                    
                }),
                function(data) {
                    expect(typeof(data) === "string").toEqual(true);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});