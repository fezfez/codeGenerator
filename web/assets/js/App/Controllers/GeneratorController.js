define(
    [
        "App/App",
        "Services/SourceService",
        "Services/GeneratorService",
        "Services/ViewFileService",
        "Services/WaitModalService",
        "Services/GenerateService",
        "Services/HistoryService",
        "Services/SearchGeneratorService",
        "Services/DownloadGeneratorService",
        "Corp/Context/Context",
        "Corp/Context/Config",
        "HighLighterPHP"
    ],
    function (
        GeneratorApp,
        SourceService,
        GeneratorService,
        ViewFileService,
        WaitModalService,
        GenerateService,
        HistoryService,
        SearchGeneratorService,
        DownloadGeneratorService,
        Context,
        Config
    ) {
        "use strict";

        GeneratorApp.controller("GeneratorCtrl",
                [
                 '$scope',
                 '$sce',
                 'SourceService',
                 'GeneratorService',
                 'ViewFileService',
                 'WaitModalService',
                 'GenerateService',
                 'HistoryService',
                 'SearchGeneratorService',
                 'DownloadGeneratorService',
                function (
                        $scope,
                        $sce,
                        $sourceService,
                        $generatorService,
                        $viewFileService,
                        $WaitModalService,
                        $generateService,
                        $historyService,
                        $searchGeneratorService,
                        $downloadGeneratorService
                    ) {

            var context = new Context(),
                config = new Config();

            $scope.fileTemplate      = __BASEPATH__ + 'assets/js/App/Template/file.html';
            $scope.configQuestion    = {};
            $scope.answers           = {};
            $scope.metadataSelected  = null;
            $scope.generatorSelected = null;
            $scope.backendSelected   = null;
            $scope.historyQuestion   = {};
            $scope.generatorPreview  = null;
            $scope.downloadLog       = [];

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

            var generate = function(context, metadata_nocache, waitFileList) {
                if (metadata_nocache === undefined ) {
                    metadata_nocache = false;
                }
                if (waitFileList === undefined ) {
                    waitFileList = false;
                }

                if (waitFileList === true) {
                    $scope.waitFileList = true;
                } else {
                    $WaitModalService.show();
                }
                $generatorService.build(
                    context,
                    metadata_nocache,
                    function(context) {
                        loadContext(context);
                        if (waitFileList === true) {
                            $scope.waitFileList = false;
                        } else {
                            $WaitModalService.hide();
                        }
                        $('body').tooltip({
                            selector: "[rel=tooltip]"
                        });
                    },
                    function(error) {
                        if (waitFileList === true) {
                            $scope.waitFileList = false;
                        } else {
                            $WaitModalService.hide();
                        }
                        $scope.unsafeModal = {
                            'title' : 'Error',
                            'body' : error
                        };
                    }
                );
            };

            $scope.setConfigQuestion = function(attribute) {
                $scope.backendConfig();
            };

            $scope.backendConfig = function() {
                $sourceService.config(
                    $scope.configQuestion,
                    function(context, data) {
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
                            $scope.backendCollection = context.getBackendCollection();
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

            $scope.previewGenerator = function(generator) {
                $scope.generatorPreview = generator;
                $scope.downloadEnd = null;
                $scope.generatorPreviewReadme = null;
                $scope.generatorPreviewGithub = null;
                $searchGeneratorService.detail(
                    generator.id,
                    function(data) {
                        $scope.generatorPreviewReadme = $sce.trustAsHtml(data.package_details.readme);
                        $scope.generatorPreviewGithub = data.package_details.github;
                    },
                    function(results) {
                        btn.button('reset');
                        $scope.unsafeModal = {
                            'title' : 'Error on generator ',
                            'body'  : results.error,
                        };
                    }
                );
            };

            $scope.downloadGenerator = function(generator) {
                $scope.downloadLog = [];
                $downloadGeneratorService.download(
                    generator,
                    function(event) {
                        event.data = $sce.trustAsHtml(event.data);
                        $scope.downloadLog.push(event);
                        $scope.downloadEnd = event.end;
                        $scope.$apply();
                    },
                    function(data) {
                        
                    }
                );
            };

            $scope.searchGenerator = function() {
                var modal = $('searchgenerator > div');
                if (!modal.hasClass( "show" )) {
                    $WaitModalService.show();
                }
                var btn = $('#search-generator-btn');
                btn.button('loading');
                $searchGeneratorService.generate(
                        $scope.searchGeneratorName,
                        function(context) {
                            if (!modal.hasClass( "show" )) {
                                $WaitModalService.hide();
                                modal.modal('show');
                            }
                            btn.button('reset');

                            $scope.searchGeneratorCollection = context.getSearchGeneratorCollection();
                        },
                        function(results) {
                            btn.button('reset');
                            $scope.unsafeModal = {
                                'title' : 'Error on generator ',
                                'body'  : results.error,
                            };
                        }
                );
            };

            $scope.destructMetadataCache = function() {
                generate(context, true);
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
                generate(context, undefined, true);
            };
            generate(context);

            /*
             * Preview file
             */
            $scope.viewFile = function (file) {
                $WaitModalService.show();
                $viewFileService.generate(context, file, function (results) {
                    $WaitModalService.hide();

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
