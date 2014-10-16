require([
    'App/App',
    'JQuery'
    ],
    function(GeneratorApp) {
    'use strict';

    GeneratorApp.directive('modal', ['$compile', '$http', '$templateCache', '$timeout', function($compile, $http, $templateCache, $timeout) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watch('modal', function(modal) {
                    if (angular.isObject(modal)) {
                        $http.get(__BASEPATH__ + 'assets/js/App/Template/modal.html').success(function(template) {
                            element.html(template);
                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');

                            if (scope.modal.callback !== undefined) {
                                $timeout(function () { scope.modal.callback(); }, 1000);
                            }
                      });
                    } else if(null === modal) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
});