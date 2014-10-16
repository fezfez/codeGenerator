require(['App/App', 'Corp/Generator/GeneratorCollectionDTO', 'JQuery'], function(app, GeneratorCollectionDTO) {
    'use strict';

    var modalURL = __BASEPATH__ + 'assets/js/App/Template/SearchGeneratorModal.html';
    app.directive('searchGeneratorModal', ['$compile', '$http', '$templateCache', function($compile, $http, $templateCache) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watch('searchGenerator', function(searchGenerator) {
                    if ((searchGenerator instanceof GeneratorCollectionDTO) === true) {
                        $http.get(modalURL).success(function(template) {
                            element.html(template);

                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');
                      });
                    } else if (searchGenerator === null) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
});