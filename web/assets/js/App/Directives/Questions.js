(function () {
    "use strict";

    GeneratorApp.directive('questions', ['$compile', '$http', function($compile, $http) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watchCollection('questionsList', function(questionsList, old) {
                    if (angular.isObject(questionsList)) {
                        $http.get(__BASEPATH__ + 'assets/js/App/Template/Question.html').success(function(template) {
                            element.html(template);
                            $compile(element.contents())(scope);
                        });
                    } else if(null === questionsList) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
}());