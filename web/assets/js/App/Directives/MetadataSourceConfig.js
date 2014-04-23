require(["App/App"], function (GeneratorApp) {
    "use strict";

    GeneratorApp.directive('metadatasource', ['$compile', '$http', '$templateCache', '$timeout', function($compile, $http, $templateCache, $timeout) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {
                scope.$watch('metadataSourceConfigForm', function(modal) {
                    if (angular.isObject(modal)) {
                        $http.get(__BASEPATH__ + 'assets/js/App/Template/MetaDataSourceConfig.html').success(function(template) {
                            element.html(template);
                            $compile(element.contents())(scope);
                            $(element.contents()).modal('show');

                            if (scope.metadataSourceConfigForm.callback !== undefined) {
                                $timeout(function () { scope.metadataSourceConfigForm.callback(); }, 1000);
                            }
                      });
                    } else {
                        $(element.contents()).modal('hide');
                    }
                });
            }
        };
    }]);
});