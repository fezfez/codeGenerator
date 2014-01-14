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
        transclude: true,
        scope: {family: '='},
        link: function(scope, element, attrs) {
            
            var template = '<ul>' + 
	            '<li class="file" ng-repeat="file in family.files">' +
	            '<span ng-click="viewFile(file)">{{ file.name }}</span>' + 
	            '</li>'+
	            '<li ng-repeat="child in family.children">'+
	            	'<span class="directory">{{ child.name }}</span>' +
	                '<file family="child"><div ng-transclude></div></file>' +
	            '</li>' +
            '</ul>';
            scope.$watch('family', function(fileList, old) {
            	console.log('IM CHANGING');
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