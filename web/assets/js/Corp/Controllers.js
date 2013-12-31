// Define our AngularJS application module.
var GeneratorApp = angular.module( "GeneratorApp", [] );

GeneratorApp.controller("GeneratorCtrl", function($scope, $http) {
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    $http.get('list-backend').success(function(data, status, headers, config) {
        $scope.backendList = data.backend;
    });
    $scope.change = function() {
        var datas =  $.param({backend: $scope.backEnd});
        $http(
            {
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                method: 'POST',
                url: 'metadata',
                data : datas
            }
        ).success(function(data, status, headers, config) {
            if(data.config !== undefined) {
                $('#configuration-modal .modal-body').empty(); $('#configuration-modal .modal-body')
                $('#configuration-modal .modal-body').append(msg.config);
                $('#configuration-modal').modal('show');
                $("#configuration-modal form").submit(function(){
                    $.ajax({
                        type:"POST",
                        data: $(this).serialize() + '&'+$('#form_Backend').serialize(),
                        url : $(this).attr('action')
                    }).done(function(data){
                        if (data.error !== undefined) {
                            $('#alert-config').remove();
                            $("#configuration-modal .modal-body").prepend('<div id="alert-config" class="alert alert-danger fade in">' + data.error + '</div>');
                        } else {
                            $('#configuration-modal').modal('hide');
                        }
                    });
                    return false;
                });
            } else if (data.metadatas !== undefined) {
                $scope.metadataList = data.metadatas;
                
                $http.get('list-generator').success(function(data, status, headers, config) {
                    $scope.generatorsList = data.generators;
                });
            }
        }).error(function(data, status, headers, config) {
            alert('Une erreur');
        });
    };

    $scope.handleGenerator = function(newValue, oldValue) {
		var datas =  $.param({backend: $scope.backEnd, generator : newValue, metadata : $scope.metadata, questions : $('.questions').serialize()});
        $.ajax({
            type: "POST",
            url: "generator",
            data: datas
        })
        .done(function( data ) {

            var countProfondeurMax = null,
                profondeur = null,
                profondeurFiles = new Array(),
                filesByValue = swapJsonKeyValues(data.generator.files);
            $.each(data.generator.files, function(id, name) {

                if(typeof name != 'string') {
                    return;
                }
                profondeur = name.split('/').length;
                countProfondeurMax = (countProfondeurMax < profondeur) ? profondeur : countProfondeurMax;
                
                if(profondeurFiles[profondeur] === undefined) {
                    profondeurFiles[profondeur] = new Array();
                }

                profondeurFiles[profondeur].push({'name' : name, 'template' : filesByValue[name]});
                
            });
            
            profondeurFiles = profondeurFiles.filter(function(n){return n});
            
            $scope.$apply(function () {
            	$scope.fileList = profondeurFiles;
            	if (newValue !== oldValue) {
            		$scope.questionsList = data.generator.questions;
            	}
            });
        });
    };
    $scope.$watch('generators', function(newValue, oldValue) {
    	if(newValue !== undefined) {
    		$scope.handleGenerator(newValue, oldValue);
    	}
    });
    
    $scope.viewFile = function(file) {
        $.ajax({
            type: "POST",
            url: "view-file",
            data : {
                generator: $scope.generators,
                file : file.template,
                backend : $scope.backEnd,
                metadata : $scope.metadata,
                questions : $('.question').serialize()
            }
        }).done(function(data) {
            $('#configuration-modal .modal-title').empty().append(name);
            $('#configuration-modal .modal-body').empty().append('<pre class="brush: php;">' + data.generator + '<pre>');
            $('#configuration-modal').modal('show');
            SyntaxHighlighter.highlight();
        });
    };
});

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


function swapJsonKeyValues(input) {
    var one, output = {};
    for (one in input) {
        if (input.hasOwnProperty(one)) {
            output[input[one]] = one;
        }
    }
    return output;
}
/*
var generator = {
    oldGenerator : null,
    ajax : function() {
        var self = this;
        $.ajax({
            type: "POST",
            url: "generator",
            data: {
                generator: $('#form_Generator').val(),
                backend: $('#form_Backend').val(),
                metadata : $('#form_Metadata').val(),
                questions : $('#questions').serialize()
            }
        })
        .done(function( data ) {

            var countProfondeurMax = null,
                profondeur = null,
                profondeurFiles = new Array(),
                filesByValue = swapJsonKeyValues(data.generator.files);
            $.each(data.generator.files, function(id, name) {

                if(typeof name != 'string') {
                    return;
                }
                profondeur = name.split('/').length;

                if(countProfondeurMax < profondeur) {
                    countProfondeurMax = profondeur;
                }
                if(profondeurFiles[profondeur] === undefined) {
                    profondeurFiles[profondeur] = new Array();
                }
                profondeurFiles[profondeur].push({'name' : name});
            });

            $('#test').empty();
            var countFile = 0;
            for(var i = 1 ; i <= countProfondeurMax ; i++){
                if(profondeurFiles[i] !== undefined) {
                    $('#test').append('<div class="col-lg-' + Math.floor(12 / countProfondeurMax) + '" id="test-' + i +'"></div>');
                    $.each(profondeurFiles[i], function(id, name) {
                        var tmp = $('#test-' + i + '').append('<div class="file" id="file-' + '-' + countFile + '">' + name + '</div>');
                        $('#file-' + '-' + countFile).on('click', function() {
                            $.ajax({
                                type: "POST",
                                url: "view-file",
                                data : {
                                    generator: $('#form_Generator').val(),
                                    file : filesByValue[name],
                                    backend : $('#form_Backend').val(),
                                    metadata : $('#form_Metadata').val(),
                                    questions : $('#questions').serialize()
                                }
                            }).done(function(data) {
                                $('#configuration-modal .modal-title').empty().append(name);
                                $('#configuration-modal .modal-body').empty().append('<pre class="brush: php;">' + data.generator + '<pre>');
                                $('#configuration-modal').modal('show');
                                SyntaxHighlighter.highlight();
                            });
                        });
                        countFile++;
                    });
                }
            }

            if(self.oldGenerator !== $('#form_Generator').val()) {
                $.each(data.generator.questions, function(id, name) {
                    $('#questions').append(
                         '<div class="form-group">'+
                            '<label for="' + name.dtoAttribute + '">' + name.text + '</label>'+
                            '<input class="form-control" id="' + name.dtoAttribute + '" type="text" name="' + name.dtoAttribute + '" placeholder="' + name.text + '" />' +
                        '</div>'
                    );
                });
                $('#questions input').on('keyup', function() {
                    self.ajax();
                });
            }
            
            self.oldGenerator = $('#form_Generator').val();
        });
    }
};*/