require(
	["Controllers/GeneratorController", 'Services/GeneratorService', 'Services/ViewFileService', 'Services/WaitModalService'], 
	function(GeneratorController, GeneratorService, ViewFileService, WaitModalService, GenerateService) {
    "use strict";
    console.log('generatorController.test.js outside describ');
    describe("Test generatorController", function () {

    	console.log('generatorController.test.js inside describ');
        var GeneratorController, scope, $httpBackend, GeneratorService, ViewFileService, WaitModalService, GenerateService;

        /*beforeEach(inject(function($injector) {
            $httpBackend = $injector.get('$httpBackend');
            $rootScope = $injector.get('$rootScope');
            $scope = $rootScope.$new();


            var $controller = $injector.get('$controller');

            createController = function() {
                return $controller('GeneratorCtrl', {
                    '$scope': $scope
                });
            };
        }));*/
        
        it("should give me true", function () {
        	console.log('fezfezfzefze');
            expect(true).toBe(false);
        });
    });
}, function (err) {
	console.log(err);
}
);