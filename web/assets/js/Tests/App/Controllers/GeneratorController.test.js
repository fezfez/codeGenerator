define(
	['Angular', 'AngularMock', "Corp/Generator/GeneratorDto", "Controllers/GeneratorController"], 
	function(angular, mock, GeneratorDto) {
    "use strict";
    describe("Test generatorController", function () {

        var controller, scope, httpBackend, sce, __SourceService__ ;

        beforeEach((function() {
            angular.mock.module('GeneratorApp');
            angular.mock.inject(function($controller, $httpBackend, $sce, $rootScope) {
                // Set up the mock http service responses
                httpBackend       = $httpBackend;
                scope             = $rootScope.$new();
                sce               = $sce;

                controller        = $controller('GeneratorCtrl', {
                    '$scope'        : scope,
                    '$sce'          : sce
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
        	var generator = new GeneratorDto();
        	scope.previewGenerator(generator);
        });
        
        it('test downloadGenerator', function() {
        	var generator = new GeneratorDto();
        	controller.downloadGenerator(generator);
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