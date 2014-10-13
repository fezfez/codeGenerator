define(function() {

    function SourceDTO() {
        var questionCollection = null,
            error              = null,
            valid              = null;

        this.setQuestionCollection = function(value) {
            questionCollection = value;
            return this;
        };
        this.setError = function(value) {
            error = (value === undefined) ? null : value;
            return this;
        };
        this.setValid = function(value) {
            valid = value;
            return this;
        };

        this.getQuestionCollection = function() {
            return questionCollection;
        };
        this.isErrored = function() {
            return (error !== null) ? true : false;
        };
        this.getError = function() {
            return error;
        };
        this.isValid = function() {
            return (valid === true) ? true : false;
        };
    }

    return SourceDTO;
});