define(function (require) {
    "use strict";

     var app                = require('App/App'),
         SourceService      = require('App/Services/SourceService'),
         GeneratorService   = require('App/Services/GeneratorService'),
         WaitModalService   = require('App/Services/WaitModalService'),
         GenerateDAOFactory = require('Corp/Generate/GenerateDAOFactory'),
         HistoryService     = require('App/Services/HistoryService'),
         Context            = require('Corp/Context/Context'),
         Config             = require('Corp/Context/Config'),
         HighLighterPHP     = require('HighLighterPHP'),
         _                  = {};

    function GeneratorController(
        $scope,
        $sourceService,
        $generatorService,
        $WaitModalService,
        $GenerateDAOFactory,
        $historyService
    ) {
        _.vm                   = $scope;
        _.generateDAOFactory   = $GenerateDAOFactory;
        _.context              = new Context(),
        _.config               = new Config();
    
        _.loadContext = function(context) {
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
    
        _.generate = function(context, metadata_nocache, waitFileList) {
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
            $generatorService.process(context, metadata_nocache).then(
                function(context) {
                    _.loadContext(context);
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
    

        _.vm.generate              = this.generate;
        _.vm.backendConfig         = this.backendConfig;
        _.vm.generate              = this.generate;
        _.vm.viewFile              = this.viewFile;
        _.vm.previewGenerator      = this.previewGenerator;
        _.vm.searchGenerator       = this.searchGenerator;
        _.vm.destructMetadataCache = this.destructMetadataCache;
        _.vm.showHistory           = this.showHistory;
        _.vm.history               = this.history;
        _.vm.setGenerator          = this.setGenerator;
        _.vm.setQuestion           = this.setQuestion;
        _.vm.setMetadata           = this.setMetadata;
        _.vm.setConfigQuestion     = this.setConfigQuestion;
        _.vm.setBackend            = this.setBackend;

        _.vm.fileTemplate      = __BASEPATH__ + 'assets/js/App/Template/file.html';
        _.vm.configQuestion    = {};
        _.vm.answers           = {};
        _.vm.metadataSelected  = null;
        _.vm.generatorSelected = null;
        _.vm.backendSelected   = null;
        _.vm.historyQuestion   = {};
        _.vm.generatorPreview  = null;
        _.vm.download          = [];

        _.generate(_.context);
    }

    GeneratorController.prototype.history = function() {
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
    
    GeneratorController.prototype.backendConfig = function() {
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

    GeneratorController.prototype.generate = function() {
        _.generateDAOFactory.getInstance().generate(_.context).then(
            function (results) {
            if (undefined !== results.error) {
                _.vm.unsafeModal = {
                    'title' : 'Error',
                    'body' : results.error
                };
            } else if (null !== results.conflictList) {
                _.vm.conflictList = results.conflictList;
            } else if (null !== results.log) {
                _.vm.unsafeModal = {
                    'title' : 'Generated succefuly',
                    'body' : results.log.join('<br/>'),
                };
            }
        });
    };
    
    GeneratorController.prototype.setBackend = function(name) {
        _.context.setBackend(name);
        _.vm.backendSelected = name;
        _.generate(_.context);
    };
    
    GeneratorController.prototype.setConfigQuestion = function(attribute) {
        $scope.backendConfig();
    };

    GeneratorController.prototype.previewGenerator = function(generator) {
        $scope.generatorPreview       = generator;
        $scope.download.end           = null;
        $scope.generatorPreviewReadme = null;
        $scope.generatorPreviewGithub = null;

        $generatorService.findOneByName(generator.id).then(
            function(data) {
                $scope.generatorPreviewReadme = data.readme;
                $scope.generatorPreviewGithub = data.github;
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

    GeneratorController.prototype.downloadGenerator = function(generator) {
        $generatorService.download(
            generator,
            function(download) {
                $scope.download = download;
                $scope.$apply();
            },
            function(data) {
                
            }
        );
    };

    GeneratorController.prototype.searchGenerator = function() {
        var modal = $('searchgenerator > div');
        if (!modal.hasClass( "show" )) {
            $WaitModalService.show();
        }
        var btn = $('#search-generator-btn');
        btn.button('loading');
        $generatorService.searchByName($scope.searchGeneratorName).then(
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

    GeneratorController.prototype.destructMetadataCache = function() {
        _.generate(context, true);
    };

    GeneratorController.prototype.setMetadata = function(name) {
        _.context.setMetadata(name);
        _.vm.metadataSelected = name;
        _.generate(context);
    };

    GeneratorController.prototype.setGenerator = function(name) {
        _.context.setGenerator(name);
        _.vm.generatorSelected = name;
        _.generate(context);
    };

    GeneratorController.prototype.setQuestion = function(attribute) {
        _.context.setQuestion(attribute, $scope.answers[attribute]);
        _.generate(context, undefined, true);
    };

    /*
     * Preview file
     */
    GeneratorController.prototype.viewFile = function (file) {
        $WaitModalService.show();
        $generatorService.viewFile(context, file).then(function (results) {
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
    
    GeneratorController.prototype.showHistory = function() {
        $historyService.generate(
            $scope.historyQuestion,
            function(context) {
                if (context.getHistoryCollection() !== null) {
                    $scope.historyCollection = context.getHistoryCollection();
                }
                $scope.openHistory = true;
            },
            function(results) {
                $scope.unsafeModal = {
                    'title' : 'Error on history ',
                    'body'  : results.error,
                };
            }
        );
    };

    GeneratorController.$inject = [
        '$scope',
        'SourceService',
        'GeneratorService',
        'WaitModalService',
        'GenerateDAOFactory',
        'HistoryService'
    ];

    app.controller("GeneratorCtrl", GeneratorController);
});
