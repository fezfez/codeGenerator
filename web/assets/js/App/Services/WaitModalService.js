GeneratorApp.factory('WaitModalService', ['$http', function ($http) {
	"use strict";

    return {
        show: function() {
            $http.get(__BASEPATH__ + 'assets/js/App/Template/WaitModal.html').success(function(template) {
            	$('waitModal').html(template);
            	$('waitModal > div').modal('show');
          });
        },
        hide: function () {
        	$('waitModal > div').modal('hide');
        }
    };
}]);