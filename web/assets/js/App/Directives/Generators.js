(function () {
    "use strict";

    GeneratorApp.directive('generators', function($compile) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {
                var template = '<select class="form-control" id="generators" name="generators" ng-model="generators" ng-options="obj.label as obj.label for obj in generatorsList">'+
                '<option value="">Select Generator</option>' +
                '</select>';
                scope.$watch('generatorsList', function(generatorsList) {
                    if (angular.isObject(generatorsList)) {
                        element.html(template);
                        $compile(element.contents())(scope);
                    } else if(null === generatorsList) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    });
}());