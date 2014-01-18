GeneratorApp.factory('WaitModalService', ['$http', function ($http) {
	"use strict";

    return {
        show: function() {
            $http.get('assets/js/App/Template/WaitModal.html').success(function(template) {
            	$('waitModal').html(template);
            	$('waitModal > div').modal('show');
          });
        },
        hide: function () {
        	$('waitModal > div').modal('hide');
        }
    };
}]);