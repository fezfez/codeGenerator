// Define our AngularJS application module.
var GeneratorApp = angular.module( "GeneratorApp", [] );

GeneratorApp.controller("GeneratorCtrl", function($scope, $http) {
	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    $http.get('backend').success(function(data, status, headers, config) {
    	$scope.backendList = data.backend;
    });
	$scope.change = function() {
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
                $('#configuration-modal .modal-body').empty(); $('#configuration-modal .modal-body')
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
	            /*
	             $("#expensesform").append( $compile("<input type='text' ng-model='expense.age' />")($scope) );
	            */
                var metadatasString = '';
                $.each(data.metadatas, function(name) {
                    metadatasString += '<option value="' + name +'">' + name + '</option>';
                });
                $('#formBackend').append(
                    '<div class="form-group">'+
                        '<label for="form_Backend" class="control-label required">Metadata</label>'+
                        '<div class="controls">'+
                            '<select name="form[Metadata]" id="form_Metadata">'+
                                metadatasString +
                            '</select>'+
                        '</div>'+
                    '</div>'
                );
            }
	    }).error(function(data, status, headers, config) {
	    	alert('Une erreur');
	    });
    };
});