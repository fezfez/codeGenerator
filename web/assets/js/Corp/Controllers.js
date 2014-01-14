GeneratorApp.controller("GeneratorCtrl", ['$scope', '$http', 'GeneratorService', '$rootScope', function($scope, $http, $generatorService, $rootScope) {
    $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    $http.get('list-backend').success(function(data, status, headers, config) {
        $scope.backendList = data.backend;
    });

    $scope.backendChange = function() {
        var datas =  $.param({backend: $scope.backEnd});
        $http(
            {
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                method: 'POST',
                url: 'metadata',
                data : datas
            }
        ).success(function(data, status, headers, config) {
            if(data.config !== undefined) {
                $('#configuration-modal .modal-body').empty();
                $('#configuration-modal .modal-body').append(msg.config);
                $('#configuration-modal').modal('show');
                $("#configuration-modal form").submit(function(){
                    $.ajax({
                        type:"POST",
                        data: $(this).serialize() + '&'+$('#form_Backend').serialize(),
                        url : $(this).attr('action')
                    }).done(function(data){
                        if (data.error !== undefined) {
                            $('#alert-config').remove();
                            $("#configuration-modal .modal-body").prepend('<div id="alert-config" class="alert alert-danger fade in">' + data.error + '</div>');
                        } else {
                            $('#configuration-modal').modal('hide');
                        }
                    });
                    return false;
                });
            } else if (data.metadatas !== undefined) {
                $scope.metadataList = data.metadatas;
                
                $http.get('list-generator').success(function(data, status, headers, config) {
                    $scope.generatorsList = data.generators;
                });
            }
        }).error(function(data, status, headers, config) {
            alert('Une erreur');
        });
    };

    $scope.handleGenerator = function(generatorName, oldGeneratorName) {
        var datas =  $.param({
            backend: $scope.backEnd,
            generator : generatorName,
            metadata : $scope.metadata,
            questions : $('.questions').serialize()
        });

        $generatorService.events(datas, (generatorName !== oldGeneratorName));
    };
    
    $scope.$watch('generators', function(generatorName, oldGeneratorName) {
        if(generatorName !== undefined) {
        	$scope.handleGenerator(generatorName, oldGeneratorName);
        }
    });

    $scope.viewFile = function(file) {
    	console.log(file);
        $.ajax({
            type: "POST",
            url: "view-file",
            data : {
                generator: $scope.generators,
                skeletonPath : file.skeletonPath,
                file : file.template,
                backend : $scope.backEnd,
                metadata : $scope.metadata,
                questions : $('.questions').serialize()
            }
        }).done(function(data) {
            $('#configuration-modal .modal-title').empty().append(name);
            $('#configuration-modal .modal-body').empty().append('<pre class="brush: php;">' + data.generator + '<pre>');
            $('#configuration-modal').modal('show');
            SyntaxHighlighter.highlight();
        });
    };
}]);

