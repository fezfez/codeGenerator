define(function(require) {
    'use strict';

    var ContextHydrator        = require('Corp/Context/ContextHydrator'),
        GeneratorDTO           = require('Corp/Generator/GeneratorDto'),
        GeneratorCollectionDTO = require('Corp/Generator/GeneratorCollectionDTO'),
        _                      = {};

    function GeneratorHydrator($sce, contextHydrator) {

        if ((contextHydrator instanceof ContextHydrator) === false) {
            throw new Error('contextHydrator must be instance of ContextHydrator');
        }

        _.contextHydrator = contextHydrator;
        _.$sce            = $sce;
    }

    GeneratorHydrator.prototype.hydrateCollection = function(data)
    {
        var rawGenerator        = _.contextHydrator.findByDtoAttributAndDeleteFromQuestion('search_generatorcollection', data),
            collection          = [],
            generatorDTO        = null,
            generatorCollection = new GeneratorCollectionDTO(),
            self                = this;

        if (rawGenerator !== null) {
            angular.forEach(rawGenerator.values, function(generator, index) {
                generatorDTO = new GeneratorDTO();

                generatorDTO.setId(generator.id)
                generatorDTO.setLabel(generator.label);

                collection.push(self.hydrate(generatorDTO, generator));
            });
        }

        generatorCollection.setCollection(collection);

        return generatorCollection;
    };

    GeneratorHydrator.prototype.hydrate = function(generatorDTO, data)
    {
        if (data.package_details !== null && data.package_details !== undefined) {
            generatorDTO.setReadme(_.$sce.trustAsHtml(data.package_details.readme));
            generatorDTO.setGithub(data.package_details.github);
        }

        return generatorDTO
    };

    return GeneratorHydrator;
});