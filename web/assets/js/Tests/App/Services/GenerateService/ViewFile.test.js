define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Corp/File/FileDataObject', 'Services/GenerateService'],
    function(angular, mock, Context, FileDataObject)
{
    describe('Testing GenerateService, viewFile method', function() {

        var httpBackend = undefined, _GenerateService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(GenerateService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend       = $httpBackend;
                _GenerateService_ = GenerateService;
            });
        }));

        it('Should return the preview file in callback', function() {

            var generateData = {
                id: 1,
                name: "banana"
            };
            httpBackend.whenPOST("generator").respond(generateData);

            _GenerateService_.viewFile(new Context(), new FileDataObject()).then((function(data) {
                expect(data).toEqual(generateData);
            }));

            httpBackend.flush();
        });

        it('Should return the error data', function() {


            httpBackend.expectPOST('generator').respond(500, '');

            _GenerateService_.viewFile(new Context(), new FileDataObject()).then((function(data) {
                expect(data).toEqual('');
            }));

            httpBackend.flush();
        });

        it('Should throw exception on wrong context type', function() {
            expect(function() {
                _GenerateService_.viewFile('im wrong', new FileDataObject()).then((function(data) {}))
            }).toThrow();
        });

        it('Should throw exception on wrong file type', function() {
            expect(function() {
                _GenerateService_.viewFile(new Context(), 'im wrong').then((function(data) {}))
            }).toThrow();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});