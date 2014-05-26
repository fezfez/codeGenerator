define([
    "App/App",
    "Corp/Directory/DirectoryDataObject",
    "Corp/Directory/DirectoryBuilderFactory",
    "Corp/Context/Context"
    ],
    function(app, DirectoryDataObject, DirectoryBuilderFactory, Context) {
    "use strict";

    var Service = app.service('GeneratorService', ['$http', '$q', function ($http, $q) {

        var http = false;

        /*
         * Generate a preview
         * @param context Corp/Context/Context
         * @param callbackAfterAjax callable
         */
        this.build = function (context, callbackAfterAjax) {
            var canceler = $q.defer();

            if ((context instanceof Context) === false) {
                throw new Error("Context muse be instance of Context");
            }

            if (http === true) {
                //canceler.resolve();
            }

            http = true;

            var datas =  $.param({
                backend   : context.getBackend(),
                metadata  : context.getMetadata(),
                generator : context.getGenerator()
            }) + '&' + $.param(context.getQuestion());


            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded'},
                    method  : "POST",
                    url     : __BASEPATH__ + "metadata",
                    data    : datas,
                    timeout : canceler.promise
                }
            ).success(function (data) {
                var dataToReturn = data;
                
                var findByDtoAttributAndDeleteFromQuestion = function (dtoAttribute) {
                    var foundedElement = null;
                    angular.forEach(data.question, function(question, index) {
                        if (question.dtoAttribute === dtoAttribute) {
                            foundedElement = question;
                            delete data.question[index];
                            data.question = data.question.filter(function(n){ return n !== undefined; });
                        }
                    });

                    return foundedElement;
                };

                dataToReturn.backendCollection = findByDtoAttributAndDeleteFromQuestion('backend');
                dataToReturn.metadataCollection = findByDtoAttributAndDeleteFromQuestion('metadata');
                dataToReturn.generatorCollection = findByDtoAttributAndDeleteFromQuestion('generator');

                if (data.files) {
                    var directories      = new DirectoryDataObject(''),
                        DirectoryBuilder = DirectoryBuilderFactory.getInstance();

                    angular.forEach(data.files, function (file, id) {
                        directories = DirectoryBuilder.build(file, directories);
                    });

                    dataToReturn.directories = directories;
                }

                callbackAfterAjax(dataToReturn);
                http = false;
            }).error(function(data) {
                alert(data.error);
            });
        };
    }]);

    return Service;
});