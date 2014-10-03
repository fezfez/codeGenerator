define(
	['Angular', 'AngularMock', "Controllers/GeneratorController"], 
	function(angular, mock) {
    "use strict";
    describe("Test generatorController", function () {

        var controller, scope, httpBackend, sce, __SourceService__ ;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($controller, $httpBackend, $sce, $rootScope, SourceService) {
                // Set up the mock http service responses
                httpBackend       = $httpBackend;
                scope             = $rootScope.$new();
                sce               = $sce;
                __SourceService__ = SourceService;
                controller        = $controller('GeneratorCtrl', {
                    '$scope'        : scope,
                    '$sce'          : sce,
                    'SourceService' : __SourceService__
                });
            });
        }));

        it('test setConfigQuestion', function() {
        	scope.setConfigQuestion('fez');
        });
        it('test backendConfig', function() {
        	scope.backendConfig();
        });
        
        it('test previewGenerator', function() {
        	scope.previewGenerator('im a generator');
        });
        
        it('test downloadGenerator', function() {
        	scope.downloadGenerator('im a generator');
        });
        
        it('test searchGenerator', function() {
        	scope.searchGenerator();
        });
        
        
        it('test destructMetadataCache ', function() {
        	scope.destructMetadataCache ();
        });
        it('test setMetadata ', function() {
        	scope.setMetadata ('im a');
        });
        it('test setGenerator ', function() {
        	scope.setGenerator ('im a');
        });
        it('test setBackend ', function() {
        	scope.setBackend ('im a');
        });
        it('test setQuestion ', function() {
        	scope.setQuestion ('im a');
        });
        
        
        it('test generate  ', function() {
        	scope.generate  ();
        });
        it('test showHistory  ', function() {
        	scope.showHistory  ();
        });
        it('test history  ', function() {
        	scope.history  ();
        });
        
        /*beforeEach(inject(function($injector) {
            $httpBackend = $injector.get('$httpBackend');
            $rootScope = $injector.get('$rootScope');
            $scope = $rootScope.$new();


            var $controller = $injector.get('$controller');

            createController = function() {
                return $controller('GeneratorCtrl', {
                    '$scope': $scope
                });
            };
        }));*/
        
    });
});