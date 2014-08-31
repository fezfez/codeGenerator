define(["App/App", "Corp/Context/Context", "Corp/Context/ContextHydrator"], function(app, Context, ContextHydrator) {
    "use strict";

    var Service = app.service('DownloadGeneratorService', ['$http', function ($http) {
        /*
         * History
         * @param callback callable
         * @param callbackError callable
         */
        this.download = function (history, callback, callbackError) {
            
            var datas =  $.param({
                'search_generator' : true,
                'install_new_package' : true,
                'stream'  : true,
                'search_generatorcollection' : history
            });

            var stream = new EventSource(__BASEPATH__ + 'generator?' + datas);

            stream.addEventListener('message', function (event) {
                callback({'data' : event.data, end : false});
            });

            stream.addEventListener('end', function (event) {
                stream.close();
                callback({'data' : 'Installation finished', end : true});
            });
        };
    }]);

    return Service;
});