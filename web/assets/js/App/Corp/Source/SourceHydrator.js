define(function(require) {
    'use strict';

    var ContextHydrator = require('Corp/Context/ContextHydrator'),
        SourceDTO       = require('Corp/Source/SourceDTO'),
        QuestionDTO     = require('Corp/Question/QuestionDTO'),
        QuestionItemDTO = require('Corp/Question/QuestionItemDTO'),
        _               = {};

    function SourceHydrator() {
        _.contextHydrator = new ContextHydrator();
    }

    SourceHydrator.prototype.hydrate = function(data) {
        var questionCollection = [],
            itemCollection     = [],
            sourceDTO          = new SourceDTO(),
            questionDTO        = null,
            itemDTO            = null;

        angular.forEach(data.question, function(question, index) {
            questionDTO = new QuestionDTO();
            questionDTO.setDefaultResponse(question.defaultResponse)
            questionDTO.setDtoAttribute(question.dtoAttribute);
            questionDTO.setRequired(question.required);
            questionDTO.setText(question.text);
            questionDTO.setType(question.type);

            itemCollection = [];
            angular.forEach(question.values, function(item, itemIndex) {
                itemDTO = new QuestionItemDTO();
                itemDTO.setId(item.id);
                itemDTO.setLabel(item.id);

                itemCollection.push(itemDTO);
            });

            questionDTO.setItemCollection(itemCollection);

            questionCollection.push(questionDTO);
        });

        sourceDTO.setQuestionCollection(questionCollection);
        sourceDTO.setError(data.error);
        sourceDTO.setValid(data.valid);

        return sourceDTO;
    };

    return SourceHydrator;
});