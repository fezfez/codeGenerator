require(["Controllers/GeneratorController", "AngularMocks"], function(ContactController) {
"use strict";
describe("the contactcontroller", function () {
    var contactController, scope;

    beforeEach(function () {
        //module("contact");

        inject(["$rootScope", "$controller", function ($rootScope, $controller) {
            scope = $rootScope.$new();
            contactController = $controller(ContactController, {$scope: scope});
            console.log(contactController);
        }]);
    });
    
    it("should give me true", function () {
        expect(true).toBe(true);
    });
});

});