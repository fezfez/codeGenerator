GeneratorApp.directive('metadata', function($compile) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
            var template = '<select id="metadataList" class="form-control" name="metadata" ng-model="metadata" ng-options="obj.id as obj.label for obj in metadataList">'+
                '</select>';
            scope.$watch('metadataList', function(metadataList){
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

GeneratorApp.directive('generators', function($compile) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
            var template = '<select id="generators" name="generators" ng-model="generators" ng-options="obj.id as obj.label for obj in generatorsList">'+
                '</select>';
            scope.$watch('generatorsList', function(generatorsList) {
                if (angular.isObject(generatorsList)) {
                	element.html(template);
                    $compile(element.contents())(scope);
                } else if(null === generatorsList) {
                	angular.element(element).hide();
                }
            });
        }
    };
});

GeneratorApp.directive('file', function($compile) {
    return {
        restrict: 'E',
        link: function(scope, element, attrs) {
        	
        	scope.Math = Math;
            var template = '<div ng-repeat="files in fileList" class="col-lg-{{ Math.floor(12 / fileList.length) }}" id="test-{{ $index }}">'+
            '<div class="file" ng-click="viewFile(file)" id="file-{{ $index }}" ng-repeat="file in files">{{ file.name }}</div>'+
            '</div>';
            scope.$watchCollection('fileList', function(fileList, old) {
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