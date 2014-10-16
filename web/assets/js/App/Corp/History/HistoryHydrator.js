define(function(require) {
    'use strict';

    var ContextHydrator      = require('Corp/Context/ContextHydrator'),
        HistoryDTO           = require('Corp/History/HistoryDTO'),
        HistoryCollectionDTO = require('Corp/History/HistoryCollectionDTO'),
        _                    = {};

    function HistoryHydrator() {
        _.contextHydrator = new ContextHydrator();
    }

    HistoryHydrator.prototype.hydrateCollection = function(data)
    {
        var rawHistory        = _.contextHydrator.findByDtoAttributAndDeleteFromQuestion('history', data),
            collection        = [],
            historyDTO        = null,
            historyCollection = new HistoryCollectionDTO()

        angular.forEach(rawHistory, function(history, index) {
            historyDTO = new HistoryDTO();
            historyDTO.setId(history.id)
            historyDTO.setLabel(history.label);

            collection.push(historyDTO);
        });

        historyCollection.setEmpty((data.history_empty !== undefined) ? true : false)
        historyCollection.setCollection(collection);

        return historyCollection;
    };

    return HistoryHydrator;
});