define(
    [
        "Angular",
        "App/App",
        "App/Services/GeneratorService",
        "App/Services/ViewFileService",
        "App/Services/WaitModalService",
        "App/Services/GenerateService"
    ],
    function (angular, GeneratorApp, GeneratorService, ViewFileService, WaitModalService, GenerateService) {
        "use strict";

        GeneratorApp.controller("GeneratorCtrl",
                ['$scope', '$http', 'GeneratorService', 'ViewFileService', 'WaitModalService', 'GenerateService',
                function ($scope, $http, $generatorService, $viewFileService, $WaitModalService, $generateService) {
                    
                    $scope.Answers = {};
                    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
                    $http.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest';

                    $http.get(__BASEPATH__ + 'list-backend').success(function (data) {
                        $scope.backendList = data.backend;
                    }).error(function(data) {
                        $scope.modal = {
                            'title' : 'Error',
                            'body' : '',
                            'callback' : function () {
                                $('modal .modal-body').empty().append(data.error);
                            }
                        };
                    });

                    $scope.$watch('backEnd', function (backEnd, oldValue) {

                        if (undefined === backEnd || null === backEnd) {
                            $scope.metadataList  = undefined;
                            $scope.generators    = null;
                            $scope.metadataName  = null;
                            $scope.fileList      = null;
                            $scope.questionsList = null;
                            return;
                        }

                        var datas =  $.param({backend: backEnd});
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
                                }).error(function(data) {
                                    $scope.modal = {
                                        'title' : 'Error',
                                        'body' : '',
                                        'callback' : function () {
                                            $('modal .modal-body').empty().append(data);
                                        }
                                    };
                                });
                            }
                        });
                    });

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

                    $scope.handleGenerator = function (generatorName) {
                        $WaitModalService.show();
                        var datas =  $.param({
                            backend   : $scope.backEnd,
                            generator : generatorName,
                            metadata  : $scope.metadataName,
                            questions : $('.questions').serialize()
                        });

                        $generatorService.build(datas, function (directories, questionList) {
                            $scope.fileList = directories;
                            $scope.questionsList = questionList;
                            $WaitModalService.hide();
                        });
                    };

                    $scope.$watch('metadataName', function (metadataName, oldMetadataName) {
                        if (metadataName !== undefined && metadataName !== null && 
                                $scope.generators !== undefined && $scope.generators !== null) {
                            $scope.handleGenerator($scope.generators);
                        }
                    });

                    $scope.$watch('generators', function (generatorName, oldGeneratorName) {
                        if (generatorName !== undefined && generatorName !== null) {
                            $scope.handleGenerator(generatorName);
                        }
                    });

                    $scope.generate = function() {
                        var datas = $.param({
                            generator    : $scope.generators,
                            backend      : $scope.backEnd,
                            metadata     : $scope.metadataName,
                            questions    : $('.questions').serialize(),
                            conflict     : $('.conflict_handle').serialize()
                        });

                        $generateService.generate(datas, function (results) {
                            if (undefined !== results.error) {
                                $scope.unsafeModal = {
                                    'title' : 'Error',
                                    'body' : results.error
                                };
                            } else if (null !== results.conflictList) {
                                $scope.conflictList = results.conflictList;
                            } else if (null !== results.log) {
                                $scope.unsafeModal = {
                                    'title' : 'Generated succefuly',
                                    'body' : results.log.join('<br/>')
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
                            metadata     : $scope.metadataName,
                            questions    : $('.questions').serialize()
                        });

                        $viewFileService.generate(datas, function (results) {
                            
                            if (results.error !== undefined) {
                                $scope.unsafeModal = {
                                    'title' : 'Error on generating ' + file.getName(),
                                    'body' : results.error,
                                };
                            } else {
                                $scope.unsafeModal = {
                                    'title' : file.getName(),
                                    'body' : '<pre class="brush: php;">' + results.generator + '</pre>',
                                    'callback' : function () {
                                        SyntaxHighlighter.highlight();
                                    }
                                };
                            }
                        });
                    };
                }]);
});