define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Services/GenerateService'],
    function(angular, mock, Context)
{
    describe('Testing GenerateService', function() {

        var httpBackend = undefined, _GenerateService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(GenerateService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend       = $httpBackend;
                _GenerateService_ = GenerateService;
            });
        }));

        it('Should throw error on wrong context type', function() {
            expect(function() {
                _GenerateService_.generate('im wrong', function() {}, function() {})
            }).toThrow();
        });
        it('Should return generationlLog', function() {

            var generationLog = 'im a log';
            httpBackend.whenPOST("generator").respond({'generationLog' : generationLog});

            _GenerateService_.generate(
                new Context(),
                (function(data) {
                    expect(data).toEqual({ 'log' : generationLog, 'conflictList' : null});
                }),
                function() {
                
                }
            );

            httpBackend.flush();
        });
        
        it('Should call error callback', function() {

            var myError = 'i am an error';
            httpBackend.expectPOST('generator').respond(500, {error : myError});

            _GenerateService_.generate(
                 new Context(),
                (function(data) {
                    
                }),
                function(data) {
                    expect(data).toEqual(myError);
                }
            );

            httpBackend.flush();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});