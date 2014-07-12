define([
        "Corp/Context/Context",
        "Corp/Directory/DirectoryDataObject",
        "Corp/Directory/DirectoryBuilderFactory"
      ],
      function(Context, DirectoryDataObject, DirectoryBuilderFactory) {
    "use strict";

    function ContextHydrator() {
        var findByDtoAttributAndDeleteFromQuestion = function (dtoAttribute, data) {
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

        /*
         * @param data Object
         * @param context Context
         * 
         * @return Context
         */
        this.hydrate = function(data, context) {
            context.setBackendCollection(findByDtoAttributAndDeleteFromQuestion('backend', data));
            context.setMetadataCollection(findByDtoAttributAndDeleteFromQuestion('metadata', data));
            context.setGeneratorCollection(findByDtoAttributAndDeleteFromQuestion('generator', data));
            context.setHistoryCollection(findByDtoAttributAndDeleteFromQuestion('history', data));

            if (context.getBackendCollection() !== null) {
                context.setBackend(context.getBackendCollection().defaultResponse);
                console.log(context.getBackend());
            }
            if (context.getMetadataCollection() !== null) {
                context.setMetadata(context.getMetadataCollection().defaultResponse);
            }
            if (context.getGeneratorCollection() !== null) {
                context.setGenerator(context.getGeneratorCollection().defaultResponse);
            }

            if (data.files) {
                var directories      = new DirectoryDataObject(''),
                    DirectoryBuilder = DirectoryBuilderFactory.getInstance();

                angular.forEach(data.files, function (file, id) {
                    directories = DirectoryBuilder.build(file, directories);
                });

                context.setDirectories(directories);
            }

            context.setQuestionCollection(data.question);

            return context;
        };
    }

    return ContextHydrator;
});