define(function (require) {
    "use strict";

     var app                = require('App/App'),
         SourceDAOFactory   = require('Corp/Source/SourceDAOFactory'),
         GeneratorService   = require('App/Services/GeneratorService'),
         WaitModalService   = require('App/Services/WaitModalService'),
         GenerateDAOFactory = require('Corp/Generate/GenerateDAOFactory'),
         HistoryDAOFactory  = require('Corp/History/HistoryDAOFactory'),
         Context            = require('Corp/Context/Context'),
         Config             = require('Corp/Context/Config'),
         HighLighterPHP     = require('HighLighterPHP'),
         _                  = {};

    function GeneratorController(
        $scope,
        $SourceDAOFactory,
        $generatorService,
        $WaitModalService,
        $GenerateDAOFactory,
        $HistoryDAOFactory
    ) {
        _.vm                 = $scope;
        _.generateDAOFactory = $GenerateDAOFactory;
        _.historyDAOFactory  = $HistoryDAOFactory;
        _.sourceDAOFactory   = $SourceDAOFactory;
        _.waitModalService   = $WaitModalService;
        _.generatorService   = $generatorService;
        _.context            = new Context(),
        _.config             = new Config();

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
                _.waitModalService.show();
            }
            _.generatorService.process(context, metadata_nocache).then(
                function(context) {
                    _.loadContext(context);
                    if (waitFileList === true) {
                        $scope.waitFileList = false;
                    } else {
                        _.waitModalService.hide();
                    }
                    $('body').tooltip({
                        selector: "[rel=tooltip]"
                    });
                },
                function(error) {
                    if (waitFileList === true) {
                        $scope.waitFileList = false;
                    } else {
                        _.waitModalService.hide();
                    }
                    $scope.unsafeModal = {
                        'title' : 'Error',
                        'body' : error
                    };
                }
            );
        };

        _.generate(_.context);
    }

    GeneratorController.prototype.history = function() {
        _.historyDAOFactory.getInstance().generate($scope.historyQuestion).then(
            function(historyContext) {

                loadContext(historyContext);

                context                  = historyContext;
                $scope.metadataSelected  = context.getMetadata();
                $scope.generatorSelected = context.getGenerator();
                $scope.backendSelected   = context.getBackend();

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
        _.sourceDAOFactory.getInstance().config(_.vm.configQuestion).then(
            function(sourceDto) {
                if (sourceDto.isValid() === true) {
                    _.vm.newSourceDto = null;
                } else {
                    _.vm.newSourceDto = sourceDto;
                }
            },
            function(data) {
                _.vm.unsafeModal = {
                    'title' : 'Error on config',
                    'body' : data.error,
                };
            }
        );
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

    GeneratorController.prototype.previewGenerator = function(generator) {
        _.vm.generatorPreview        = generator;
        _.vm.download.end            = null;
        _.vm.generatorPreview.readme = null;
        _.vm.generatorPreview.github = null;

        _.generatorService.findOneByName(generator.id).then(
            function(data) {
                _.vm.generatorPreview.readme = data.readme;
                _.vm.generatorPreview.github = data.github;
            },
            function(results) {
                _.vm.unsafeModal = {
                    'title' : 'Error on generator ',
                    'body'  : results.error,
                };
            }
        );
    };

    GeneratorController.prototype.downloadGenerator = function(generator) {
        _.generatorService.download(
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
        var modal = $('searchgenerator > div'),
            btn = $('#search-generator-btn');

        if (!modal.hasClass( "show" )) {
            _.waitModalService.show();
        }
        btn.button('loading');

        _.generatorService.searchByName(_.vm.searchGeneratorName).then(
            function(context) {
                btn.button('reset');
                _.waitModalService.hide();

                _.vm.searchGeneratorCollection = context.getSearchGeneratorCollection();
            },
            function(results) {
                btn.button('reset');
                _.vm.unsafeModal = {
                    'title' : 'Error on generator ',
                    'body'  : results.error,
                };
            }
        );
    };

    GeneratorController.prototype.destructMetadataCache = function() {
        _.generate(context, true);
    };

    /*
     * Preview file
     */
    GeneratorController.prototype.viewFile = function (file) {
        _.waitModalService.show();
        _.generatorService.viewFile(context, file).then(function (results) {
            _.waitModalService.hide();

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
        _.waitModalService.show();
        _.historyDAOFactory.getInstance().retrieveAll().then(
            function(historyCollection) {
                _.waitModalService.hide();
                _.vm.historyModalOpen = true;

                if (historyCollection.isEmpty() === false) {
                    _.vm.historyCollection = historyCollection.getCollection();
                }
            },
            function(results) {
                _.vm.historyModalOpen = false;
                _.waitModalService.hide();
                _.vm.unsafeModal = {
                    'title' : 'Error on history ',
                    'body'  : results.error,
                };
            }
        );
    };

    GeneratorController.prototype.setMetadata = function(name) {
        _.context.setMetadata(name);
        _.vm.metadataSelected = name;
        _.generate(_.context);
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

    GeneratorController.prototype.setBackend = function(name) {
        _.context.setBackend(name);
        _.vm.backendSelected = name;
        _.generate(_.context);
    };

    GeneratorController.prototype.setConfigQuestion = function(attribute) {
        _.vm.backendConfig();
    };

    GeneratorController.$inject = [
        '$scope',
        'SourceDAOFactory',
        'GeneratorService',
        'WaitModalService',
        'GenerateDAOFactory',
        'HistoryDAOFactory'
    ];

    app.controller("GeneratorCtrl", GeneratorController);
});
