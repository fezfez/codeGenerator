define(['App/App'], function (GeneratorApp) {
    'use strict';

    GeneratorApp.directive('conflict', ['$compile', '$http', function($compile, $http) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watchCollection('conflictList', function(conflictList, old) {
                    if (angular.isObject(conflictList)) {
                        $http.get(__BASEPATH__ + 'assets/js/App/Template/ConflictModal.html').success(function(template) {
                            element.html(template);
                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');
                        });
                    } else if(null === conflictList) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
});