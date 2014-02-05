(function () {
    "use strict";

    angular.module('GeneratorApp.controllers', [])
         .controller("GeneratorCtrl", ['$scope', '$http', 'GeneratorService', 'ViewFileService', 'WaitModalService', 'GenerateService',
                                              function ($scope, $http, $generatorService, $viewFileService, $WaitModalService, $generateService) {
                $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

                $http.get(__BASEPATH__ + 'list-backend').success(function (data) {
                    $scope.backendList = data.backend;
                });

                $scope.backendChange = function () {
                    var datas =  $.param({backend: $scope.backEnd});
                    $http(
                        {
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                            method  : 'POST',
                            url     : __BASEPATH__ + 'metadata',
                            data    : datas
                        }
                    ).success(function (data) {
                        if (data.config !== undefined) {
                        	$scope.metadataSourceConfig = {};
                            $scope.metadataSourceConfigForm = {
                                'title' : $scope.backEnd + ' configuration',
                                'questionsList' : data.config
                            };
                        } else if (data.metadatas !== undefined) {
                            $scope.metadataList = data.metadatas;

                            $http.get(__BASEPATH__ + 'list-generator').success(function (data) {
                                $scope.generatorsList = data.generators;
                            });
                        }
                    });
                };
                
                $scope.backendConfig = function() {
                    var datas =  $.param({backend: $scope.backEnd, form : $scope.metadataSourceConfig});
                    $http(
                        {
                            headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                            method  : 'POST',
                            url     : __BASEPATH__ + 'metadata-save',
                            data    : datas
                        }
                    ).success(function (data) {
                        if (data.error !== undefined) {
                        	$scope.metadataSourceConfigForm.error = data.error;
                        } else {
                        	delete $scope.metadataSourceConfigForm;
                        	$scope.backendChange();
                        }
                    });
                    return false;
                };

                $scope.handleGenerator = function (generatorName, oldGeneratorName) {
                    $WaitModalService.show();
                    var datas =  $.param({
                        backend   : $scope.backEnd,
                        generator : generatorName,
                        metadata  : $scope.metadata,
                        questions : $('.questions').serialize()
                    });

                    $generatorService.build(datas, function (directories, questionList) {
                        $scope.fileList = directories;
                        $scope.questionsList = questionList;
                        $WaitModalService.hide();
                    });
                };

                $scope.$watch('generators', function (generatorName, oldGeneratorName) {
                    if (generatorName !== undefined) {
                        $scope.handleGenerator(generatorName, oldGeneratorName);
                    }
                });
                
                $scope.generate = function() {
                    var datas = $.param({
                        generator    : $scope.generators,
                        backend      : $scope.backEnd,
                        metadata     : $scope.metadata,
                        questions    : $('.questions').serialize(),
                        conflict     : $('.conflict_handle').serialize()
                    });

                    $generateService.generate(datas, function (results) {
                        if (null !== results.conflictList) {
                            $scope.conflictList = results.conflictList;
                        } else if (null !== results.log) {
                            $scope.modal = {
                                'title' : 'Generated succefuly',
                                'body' : results.log.join('<br/>'),
                                'callback' : function () {
                                    $('modal .modal-body').empty().append(results.log.join('<br/>'));
                                }
                            };
                        }
                    });
                };

                $scope.viewFile = function (file) {
                    var datas = $.param({
                        generator    : $scope.generators,
                        skeletonPath : file.getSkeletonPath(),
                        file         : file.getOriginalName(),
                        backend      : $scope.backEnd,
                        metadata     : $scope.metadata,
                        questions    : $('.questions').serialize()
                    });

                    $viewFileService.generate(datas, function (results) {
                        $scope.modal = {
                            'title' : file.getName(),
                            'body' : results.generator,
                            'callback' : function () {
                                var content = $('modal .modal-body').html();
                                $('modal .modal-body').empty().append('<pre class="brush: php;">' + content + '</pre>');
                                SyntaxHighlighter.highlight();
                            }
                        };
                    });
                };
            }]);
}());