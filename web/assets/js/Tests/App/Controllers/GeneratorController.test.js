require(
	["Controllers/GeneratorController", 'Services/GeneratorService', 'Services/ViewFileService', 'Services/WaitModalService'], 
	function(GeneratorController, GeneratorService, ViewFileService, WaitModalService, GenerateService) {
    "use strict";
    describe("Test generatorController", function () {

        var GeneratorController, scope, $httpBackend, GeneratorService, ViewFileService, WaitModalService, GenerateService;

        beforeEach(inject(function($injector) {
            $httpBackend = $injector.get('$httpBackend');
            $rootScope = $injector.get('$rootScope');
            $scope = $rootScope.$new();


            var $controller = $injector.get('$controller');

            createController = function() {
                return $controller('GeneratorCtrl', {
                    '$scope': $scope
                });
            };
        }));
        
        it("should give me true", function () {
            expect(true).toBe(true);
        });
    });
});