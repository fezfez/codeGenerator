require(['App/App', 'JQuery'], function(app) {
    'use strict';

    var modalURL = __BASEPATH__ + 'assets/js/App/Template/HistoryModal.html';

    app.directive('historyModal', ['$compile', '$http', '$templateCache', function($compile, $http, $templateCache) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {

                scope.$watch('historyModalOpen', function(historyModalOpen) {
                    if (historyModalOpen === true) {
                        $http.get(modalURL).success(function(template) {
                            element.html(template);

                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');
                      });
                    } else if(null === historyModalOpen || false === historyModalOpen) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    }]);
});