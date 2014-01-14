(function () {
    "use strict";

	GeneratorApp.directive('questions', function($compile) {
	    return {
	        restrict: 'E',
	        link: function(scope, element, attrs) {
	            var template = '<div class="form-group" ng-repeat="questions in questionsList">'+
	            '<label for="{{ name.dtoAttribute }}">{{ questions.text }}</label>'+
	            '<input ng-keyup="handleGenerator(generators, generators)" class="form-control questions" id="{{ questions.dtoAttribute }}" type="text" name="{{ questions.dtoAttribute }}" placeholder="{{ questions.text }}" />' +
	            '</div>';
	            scope.$watchCollection('questionsList', function(fileList, old) {
	                if (angular.isObject(fileList)) {
	                    element.html(template);
	                    $compile(element.contents())(scope);
	                } else if(null === fileList) {
	                	angular.element(element).hide();
	                }
	            });
	        }
	    };
	});

}());