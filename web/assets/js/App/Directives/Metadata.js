(function () {
    "use strict";

    GeneratorApp.directive('metadata', function($compile) {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {
                var template = '<select id="metadataList" class="form-control" name="metadata" ng-model="metadata" ng-options="obj.id as obj.label for obj in metadataList">'+
                     '<option value="">Select Metadata</option>'+
                    '</select>';
                scope.$watch('metadataList', function(metadataList) {
                    if (angular.isObject(metadataList)) {
                        element.html(template);
                        $compile(element.contents())(scope);
                    } else if(null === metadataList) {
                        angular.element(element).hide();
                    }
                });
            }
        };
    });

}());