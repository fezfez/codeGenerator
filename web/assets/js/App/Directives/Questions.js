(function () {
    "use strict";

    GeneratorApp.directive('questions', ['$compile', '$http', function($compile, $http) {
        return {
            restrict: 'E',
            templateUrl: __BASEPATH__ + 'assets/js/App/Template/Question.html'
        };
    }]);
}());