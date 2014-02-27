define([
    "Angular",
    "App/App",
    "JQuery"
    ],
    function(angular, GeneratorApp) {
    "use strict";

        GeneratorApp.directive('unsafeModal', ['$compile', '$http', '$templateCache', '$timeout', '$sce', function($compile, $http, $templateCache, $timeout, $sce) {
            return {
                restrict: 'E',
                link: function(scope, element, attrs) {

                    scope.$watch('unsafeModal', function(modal) {
                        if (angular.isObject(modal)) {
                            modal.body = $sce.trustAsHtml(modal.body);
                            console.log(modal.body);
                            $http.get(__BASEPATH__ + 'assets/js/App/Template/UnsafeHtmlModal.html').success(function(template) {
                                element.html(template);
                                
                                $compile(element.contents())(scope);
                                $(element.contents()).modal('show');

                                if (scope.unsafeModal.callback !== undefined) {
                                    $timeout(function () { scope.unsafeModal.callback(); }, 1000);
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