define(function (require) {
    'use strict';

     var app                 = require('App/App'),
         SourceDAOFactory    = require('Corp/Source/SourceDAOFactory'),
         GeneratorDAOFactory = require('Corp/Generator/GeneratorDAOFactory'),
         WaitModalService    = require('Services/WaitModalService'),
         GenerateDAOFactory  = require('Corp/Generate/GenerateDAOFactory'),
         HistoryDAOFactory   = require('Corp/History/HistoryDAOFactory'),
         Context             = require('Corp/Context/Context'),
         Config              = require('Corp/Context/Config'),
         highlight           = require('highlight'),
         _                   = {};

    function GeneratorController(
        $scope,
        $SourceDAOFactory,
        $GeneratorDAOFactory,
        $WaitModalService,
        $GenerateDAOFactory,
        $HistoryDAOFactory
    ) {
        _.vm                  = $scope;
        _.generateDAOFactory  = $GenerateDAOFactory;
        _.historyDAOFactory   = $HistoryDAOFactory;
        _.sourceDAOFactory    = $SourceDAOFactory;
        _.waitModalService    = $WaitModalService;
        _.generatorDAOFactory = $GeneratorDAOFactory;
        _.context             = new Context(),
        _.config              = new Config();

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
        _.vm.searchGeneratorModalOpen = 'fezfez';

        _.loadContext = function(context) {
            if (context.getBackendCollection() !== null)
                _.vm.backendCollection = context.getBackendCollection();
            if (context.getMetadataCollection() !== null)
                _.vm.metadataCollection = context.getMetadataCollection();
            if (context.getGeneratorCollection() !== null)
                _.vm.generatorCollection = context.getGeneratorCollection();
            if (context.getDirectories() !== null)
                _.vm.fileList = context.getDirectories();
            if (context.getQuestionCollection() !== null)
                _.vm.questionList = context.getQuestionCollection();
            if (context.getHistoryCollection() !== null)
                _.vm.historyCollection = context.getHistoryCollection();
        };
    
        _.generate = function(context, metadata_nocache, waitFileList) {
            if (metadata_nocache === undefined ) {
                metadata_nocache = false;
            }
            if (waitFileList === undefined ) {
                waitFileList = false;
            }
    
            if (waitFileList === true) {
                _.vm.waitFileList = true;
            } else {
                _.waitModalService.show();
            }
            _.generateDAOFactory.getInstance().process(context, metadata_nocache).then(
                function(context) {
                    _.loadContext(context);
                    if (waitFileList === true) {
                        _.vm.waitFileList = false;
                    } else {
                        _.waitModalService.hide();
                    }
                    $('body').tooltip({
                        selector: '[rel=tooltip]'
                    });
                },
                function(error) {
                    if (waitFileList === true) {
                        _.vm.waitFileList = false;
                    } else {
                        _.waitModalService.hide();
                    }
                    _.vm.unsafeModal = {
                        'title' : 'Error',
                        'body' : error
                    };
                }
            );
        };

        _.generate(_.context);
    }

    GeneratorController.prototype.history = function() {
        _.historyDAOFactory.getInstance().generate(_.vm.historyQuestion).then(
            function(historyContext) {

                loadContext(historyContext);

                context                = historyContext;
                _.vm.metadataSelected  = context.getMetadata();
                _.vm.generatorSelected = context.getGenerator();
                _.vm.backendSelected   = context.getBackend();

                angular.forEach(context.getQuestionCollection(), function (question, id) {
                    _.vm.answers[question.dtoAttribute] = question.defaultResponse;
                });

                $('history > div').modal('hide');
            },
            function(results) {
                _.vm.unsafeModal = {
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
        _.vm.generatorPreview = generator;
        _.vm.download.end     = null;

        _.generatorDAOFactory.getInstance().findOneByName(generator).then(
            function(generator) {
                _.vm.generatorPreview = generator;
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
        _.generatorDAOFactory.getInstance().download(
            generator,
            function(download) {
                _.vm.download = download;
                _.vm.$apply();
            },
            function(data) {
                
            }
        );
    };

    GeneratorController.prototype.searchGenerator = function() {
        var btn = $('#search-generator-btn');

        if (!$('searchgenerator > div').hasClass( 'show' )) {
            _.waitModalService.show();
        }
        btn.button('loading');

        _.generatorDAOFactory.getInstance().searchByName(_.vm.searchGeneratorName).then(
            function(generatorCollection) {
                btn.button('reset');
                _.waitModalService.hide();
                _.vm.searchGenerator = generatorCollection;
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
        _.generate(_.context, true);
    };

    /*
     * Preview file
     */
    GeneratorController.prototype.viewFile = function (file) {
        _.waitModalService.show();
        _.generateDAOFactory.getInstance().viewFile(_.context, file).then(
            function (results) {
                _.waitModalService.hide();
    
                _.vm.file = {
                    'title' : file.getName(),
                    'content' : results.previewfile
                };
            },
            function (data) {
                _.waitModalService.hide();

                _.vm.unsafeModal = {
                    'title' : 'Error on generating ' + file.getName(),
                    'body' : data.error,
                };
            }
        );
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
        _.generate(_.context);
    };

    GeneratorController.prototype.setQuestion = function(attribute) {
        _.context.setQuestion(attribute, _.vm.answers[attribute]);
        _.generate(_.context, undefined, true);
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
        'GeneratorDAOFactory',
        'WaitModalService',
        'GenerateDAOFactory',
        'HistoryDAOFactory'
    ];

    app.controller('GeneratorCtrl', GeneratorController);
});
