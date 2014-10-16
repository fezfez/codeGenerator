require(['App/App', 'Corp/Source/SourceDTO', 'JQuery'], function(app, SourceDTO) {
    'use strict';

    var modalURL = __BASEPATH__ + 'assets/js/App/Template/newSource.html';

    app.directive('newSourceModal', ['$compile', '$http', '$templateCache', function($compile, $http, $templateCache) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watch('newSourceDto', function(newSourceDto) {
                    if ((newSourceDto instanceof SourceDTO) === true) {
                        $http.get(modalURL).success(function(template) {
                            element.html(template);

                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');
                      });
                    } else if (newSourceDto === null) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
});