define([
    "App/App",
    "Corp/Directory/DirectoryDataObject",
    "Corp/Directory/DirectoryBuilderFactory"
    ],
    function(app, DirectoryDataObject, DirectoryBuilderFactory) {
    "use strict";

    var Service = app.service('GeneratorService', ['$http', '$q', function ($http, $q) {

        var http = false;

        /*
         * Generate a preview
         * @param data array
         * @param callbackAfterAjax callable
         */
        this.build = function (context, callbackAfterAjax) {
            var canceler = $q.defer();

            if (http === true) {
                //canceler.resolve();
            }

            http = true;

            var datas =  $.param({
                backend   : context.getBackend(),
                metadata  : context.getMetadata(),
                generator : context.getGenerator(),
                questions : context.getQuestion()
            });

            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                    method  : "POST",
                    url     : __BASEPATH__ + "metadata",
                    data    : datas,
                    timeout : canceler.promise
                }
            ).success(function (data) {
                if (data.generator) {
                    var directories      = new DirectoryDataObject(''),
                        DirectoryBuilder = DirectoryBuilderFactory.getInstance();
    
                    angular.forEach(data.generator.files, function (file, id) {
                        directories = DirectoryBuilder.build(file, directories);
                    });
    
                    callbackAfterAjax({ "directories" : directories, "questionList" : data.generator.questions});
                } else {
                    callbackAfterAjax(data);
                }
                http = false;
            }).error(function(data) {
                alert(data.error);
            });
        };
    }]);

    return Service;
});