(function () {
    "use strict";

	GeneratorApp.directive('modal', ['$compile', '$http', '$templateCache', function($compile, $http, $templateCache) {
	    return {
	        restrict: 'E',
	        link: function(scope, element, attrs) {

	            scope.$watch('modal', function(modal) {
	                if (angular.isObject(modal)) {
	    	            $http.get('assets/js/App/Template/modal.html').success(function(template) {
		                	element.html(template);
		                    $compile(element.contents())(scope);
		                    $(element.contents()).modal('show');
		                    if (scope.modal.callback !== undefined) {
		                    	scope.modal.callback();
		                    }
    	              });
	                } else if(null === modal) {
	                	angular.element(element).hide();
	                }
	            });
	        }
	    };
	}]);
}());