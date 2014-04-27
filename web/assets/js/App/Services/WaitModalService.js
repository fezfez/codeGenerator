define(["App/App", "TwitterBootstrap"], function(app, $) {

    var Service = app.service('WaitModalService', ['$http', function ($http) {
        "use strict";

        /*
         * Show wait modal
         */
        this.show = function()
        {
            $http.get(__BASEPATH__ + 'assets/js/App/Template/WaitModal.html').success(function(template) {
                $('waitModal').html(template);
                $('waitModal > div').modal('show');
            });
        };

        /*
         * Hide wait modal
         */
        this.hide = function ()
        {
            $('waitModal > div').modal('hide');
        };
    }]);

    return Service;
});