define(function(require) {
    "use strict";

    var Context                 = require('Corp/Context/Context'),
        DirectoryDataObject     = require('Corp/Directory/DirectoryDataObject'),
        DirectoryBuilderFactory = require('Corp/Directory/DirectoryBuilderFactory');

    function ContextHydrator() {

    }

    ContextHydrator.prototype.findByDtoAttributAndDeleteFromQuestion = function (dtoAttribute, data) {
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
    ContextHydrator.prototype.hydrate = function(data, context) {
        var backendCollection  = this.findByDtoAttributAndDeleteFromQuestion('backend', data);
        var metadataCollection = this.findByDtoAttributAndDeleteFromQuestion('metadata', data);

        if (backendCollection !== null) {
            angular.forEach(backendCollection.values, function (backend, id) {
                if (metadataCollection !== null) {
                    angular.forEach(metadataCollection.values, function (metadata, id) {
                        if (backend.label === metadata.source) {
                            if (backend.metadata === undefined) {
                                backend.metadata = {};
                                backend.metadata.values = [];
                            }
                            backend.metadata.values.push(metadata);
                        }
                    });
                }
            });
        }

        context.setBackendCollection(backendCollection);
        context.setMetadataCollection(metadataCollection);
        context.setGeneratorCollection(this.findByDtoAttributAndDeleteFromQuestion('generator', data));
        context.setHistoryCollection(this.findByDtoAttributAndDeleteFromQuestion('history', data));
        context.setSearchGeneratorCollection(this.findByDtoAttributAndDeleteFromQuestion('search_generatorcollection', data));

        if (context.getBackendCollection() !== null) {
            context.setBackend(context.getBackendCollection().defaultResponse);
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

    return ContextHydrator;
});