define(function() {

    function QuestionDTO() {
        var defaultResponse = null,
            dtoAttribute    = null,
            required        = null,
            text            = null,
            type            = null,
            itemCollection  = null;

        this.setDefaultResponse = function(value) {
            defaultResponse = value;
            return this;
        };
        this.setDtoAttribute = function(value) {
            dtoAttribute = value;
            return this;
        };
        this.setRequired = function(value) {
            required = value;
            return this;
        };
        this.setText = function(value) {
            text = value;
            return this;
        };
        this.setType = function(value) {
            type = value;
            return this;
        };
        this.setItemCollection = function(value) {
            itemCollection = value;
            return this;
        };

        this.getDefaultResponse = function() {
            return defaultResponse;
        };
        this.getDtoAttribute = function() {
            return dtoAttribute;
        };
        this.getRequired = function() {
            return required;
        };
        this.getText = function() {
            return text;
        };
        this.getType = function() {
            return type;
        };
        this.getItemCollection = function() {
            return itemCollection;
        };
    }

    return QuestionDTO;
});