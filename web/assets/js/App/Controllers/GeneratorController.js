define(
    [
        "App/App",
        "App/Services/SourceService",
        "App/Services/GeneratorService",
        "App/Services/ViewFileService",
        "App/Services/WaitModalService",
        "App/Services/GenerateService",
        "App/Services/HistoryService",
        "Corp/Context/Context",
        "Corp/Context/Config",
        "HighLighterPHP"
    ],
    function (GeneratorApp, SourceService, GeneratorService, ViewFileService, WaitModalService, GenerateService, HistoryService, Context, Config) {
        "use strict";

        GeneratorApp.controller("GeneratorCtrl",
                ['$scope', '$http', 'SourceService', 'GeneratorService', 'ViewFileService', 'WaitModalService', 'GenerateService', 'HistoryService',
                function ($scope, $http, $sourceService, $generatorService, $viewFileService, $WaitModalService, $generateService, $historyService) {

            var context = new Context(),
                config = new Config();

            $scope.fileTemplate      = __BASEPATH__ + 'assets/js/App/Template/file.html';
            $scope.configQuestion    = {};
            $scope.answers           = {};
            $scope.metadataSelected  = null;
            $scope.generatorSelected = null;
            $scope.backendSelected   = null;
            $scope.historyQuestion   = {};

            $scope.setConfigQuestion = function(attribute) {
                $scope.backendConfig();
            };

            $scope.backendConfig = function() {
                $sourceService.config(
                    $scope.configQuestion,
                    function(data) {
                        if (data.question) {
                            $scope.configQuestionsList = data.question;
                        }
                        var modal = $('newSource > div');
                        if (!modal.hasClass( "show" )) {
                            modal.modal('show');
                        }
                        
                        if (data.error !== undefined) {
                            $scope.configFormError = data.error;
                        } else if (data.valid !== undefined) {
                            delete $scope.metadataSourceConfigForm;
                            $('newSource > div').modal('hide');
                        }
                },
                function(data) {
                    $scope.unsafeModal = {
                        'title' : 'Error on config',
                        'body' : data.error,
                    };
                });
            };

            var loadContext = function(context) {
                if (context.getBackendCollection() !== null)
                    $scope.backendCollection = context.getBackendCollection();
                if (context.getMetadataCollection() !== null)
                    $scope.metadataCollection = context.getMetadataCollection();
                if (context.getGeneratorCollection() !== null)
                    $scope.generatorCollection = context.getGeneratorCollection();
                if (context.getDirectories() !== null)
                    $scope.fileList = context.getDirectories();
                if (context.getQuestionCollection() !== null)
                    $scope.questionList = context.getQuestionCollection();
                if (context.getHistoryCollection() !== null)
                    $scope.historyCollection = context.getHistoryCollection();
            };

            var generate = function(context) {
                $WaitModalService.show();
                $generatorService.build(
                    context,
                    function(context) {
                        loadContext(context);
                        $WaitModalService.hide();
                        $('body').tooltip({
                            selector: "[rel=tooltip]"
                        });
                    }
                );
            };

            $scope.setMetadata = function(name) {
                context.setMetadata(name);
                $scope.metadataSelected = name;
                generate(context);
            };

            $scope.setGenerator = function(name) {
                context.setGenerator(name);
                $scope.generatorSelected = name;
                generate(context);
            };

            $scope.setBackend = function(name) {
                context.setBackend(name);
                $scope.backendSelected = name;
                generate(context);
            };
            $scope.setQuestion = function(attribute) {
                context.setQuestion(attribute, $scope.answers[attribute]);
                generate(context);
            };
            generate(context);

            /*
             * Preview file
             */
            $scope.viewFile = function (file) {
                $viewFileService.generate(context, file, function (results) {

                    if (results.error !== undefined) {
                        $scope.unsafeModal = {
                            'title' : 'Error on generating ' + file.getName(),
                            'body' : results.error,
                        };
                    } else {
                        $scope.unsafeModal = {
                            'title' : file.getName(),
                            'body' : '<pre class="brush: php;">' + results.previewfile + '</pre>',
                            'callback' : function () {
                                SyntaxHighlighter.highlight();
                            }
                        };
                    }
                });
            };

            /*
             * Generate
             */
            $scope.generate = function() {
                $generateService.generate(context, function (results) {
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
                            'body' : results.log.join('<br/>'),
                        };
                    }
                });
            };
            
            $scope.showHistory = function() {
                $historyService.generate(
                    $scope.historyQuestion,
                    function(context) {
                        var modal = $('history > div');
                        if (!modal.hasClass( "show" )) {
                            modal.modal('show');
                        }
                        if (context.getHistoryCollection() !== null)
                            $scope.historyCollection = context.getHistoryCollection();
                    },
                    function(results) {
                        $scope.unsafeModal = {
                            'title' : 'Error on history ',
                            'body'  : results.error,
                        };
                    }
                );
            };
            
            /*
             * History
             */
            $scope.history = function() {
                $historyService.generate(
                    $scope.historyQuestion,
                    function(historyContext) {

                        loadContext(historyContext);
                        context = historyContext;
                        $scope.metadataSelected = context.getMetadata();
                        $scope.generatorSelected = context.getGenerator();
                        $scope.backendSelected = context.getBackend();
                        angular.forEach(context.getQuestionCollection(), function (question, id) {
                            $scope.answers[question.dtoAttribute] = question.defaultResponse;
                        });

                        $('history > div').modal('hide');
                    },
                    function(results) {
                        $scope.unsafeModal = {
                            'title' : 'Error on history ',
                            'body'  : results.error,
                        };
                    }
                );
            };
        }]);
});