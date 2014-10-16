require(['App/App', 'highlight', 'JQuery'], function(app, highlight) {
    'use strict';

    app.directive('viewFileModal', ['$compile', '$http', '$templateCache', '$timeout', '$sce', function($compile, $http, $templateCache, $timeout, $sce) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watch('file', function(modal) {
                    if (angular.isObject(modal)) {
                        modal.content = $sce.trustAsHtml(highlight.highlightAuto(modal.content).value);
                        $http.get(__BASEPATH__ + 'assets/js/App/Template/ViewFileModal.html').success(function(template) {
                            element.html(template);

                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');
                      });
                    } else if(null === modal) {
                    	$(element.contents()).modal('hide');
                    }
                });
            }
        };
    }]);
});