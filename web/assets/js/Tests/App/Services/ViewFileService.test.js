define(
    ['Angular', 'AngularMock', 'Corp/Context/Context', 'Corp/File/FileDataObject', 'Services/ViewFileService'],
    function(angular, mock, Context, FileDataObject)
{
    describe('Testing viewFileService', function() {

        var httpBackend = undefined, _ViewFileService_ = undefined;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function(ViewFileService, $httpBackend) {
                // Set up the mock http service responses
                httpBackend       = $httpBackend;
                _ViewFileService_ = ViewFileService;
            });
        }));

        it('Should return the preview file in callback', function() {

            var generateData = {
                id: 1,
                name: "banana"
            };
            httpBackend.whenPOST("generator").respond(generateData);

            _ViewFileService_.generate(new Context(), new FileDataObject(), (function(data) {
                expect(data).toEqual(generateData);
            }));

            httpBackend.flush();
        });

        it('Should return the error data', function() {


            httpBackend.expectPOST('generator').respond(500, '');

            _ViewFileService_.generate(new Context(), new FileDataObject(), (function(data) {
                expect(data).toEqual('');
            }));

            httpBackend.flush();
        });

        it('Should throw exception on wrong context type', function() {
            expect(function() {
                _ViewFileService_.generate('im wrong', new FileDataObject(), (function(data) {}))
            }).toThrow();
        });

        it('Should throw exception on wrong file type', function() {
            expect(function() {
                _ViewFileService_.generate(new Context(), 'im wrong', (function(data) {}))
            }).toThrow();
        });

        afterEach(function() {
            httpBackend.verifyNoOutstandingExpectation();
        });
    });
});