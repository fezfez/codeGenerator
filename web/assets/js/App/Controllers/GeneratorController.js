define(
    [
        "App/App",
        "App/Services/SourceService",
        "App/Services/GeneratorService",
        "App/Services/ViewFileService",
        "App/Services/WaitModalService",
        "App/Services/PreviewService",
        "Corp/Context/Context",
        "Corp/Context/Config",
        "HighLighterPHP"
    ],
    function (GeneratorApp, SourceService, GeneratorService, ViewFileService, WaitModalService, PreviewService, Context, Config) {
        "use strict";

        GeneratorApp.controller("GeneratorCtrl",
                ['$scope', '$http', 'SourceService', 'GeneratorService', 'ViewFileService', 'WaitModalService', 'PreviewService',
                function ($scope, $http, $sourceService, $generatorService, $viewFileService, $WaitModalService, $previewService) {

            var config = new Config();
            $scope.configQuestion = {};

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
	                    } else if (data.valid === true) {
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

            $scope.answers = {};
            $scope.metadataSelected = null;
            $scope.generatorSelected = null;
            $scope.backendSelected = null;

            var generate = function(context) {
                $WaitModalService.show();
                $generatorService.build(
                    context,
                    function(data) {
                        if (data.backendCollection)
                            $scope.backendCollection = data.backendCollection;
                        if (data.metadataCollection)
                            $scope.metadataCollection = data.metadataCollection;
                        if (data.generatorCollection)
                            $scope.generatorCollection = data.generatorCollection;
                        if (data.directories)
                            $scope.fileList = data.directories;
                        if (data.question)
                            $scope.questionList = data.question;
                        $WaitModalService.hide();
                        $('body').tooltip({
                            selector: "[rel=tooltip]"
                        });
                    }
                );
            };
            var context = new Context();
            
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
                $previewService.generate(context, function (results) {
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
        }]);
});