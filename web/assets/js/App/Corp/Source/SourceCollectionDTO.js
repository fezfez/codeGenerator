define(function() {

    function SourceCollectionDTO() {
        var collection = null,
            error      = null;

        this.setCollection = function(value) {
            collection = value;
            return this;
        };
        this.setError = function(value) {
            error = value;
            return this;
        };

        this.getCollection = function() {
            return collection;
        };
        this.isErrored = function() {
            return (error !== null) ? true : false;
        };
        this.getError = function() {
            return error;
        };
    }

    return SourceCollectionDTO;
});