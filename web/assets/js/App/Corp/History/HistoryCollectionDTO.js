define(function() {

    function HistoryCollectionDTO() {
        var collection = null,
            empty      = null;

        this.setCollection = function(value) {
            collection = value;
            return this;
        };
        this.setEmpty = function(value) {
            empty = value;
            return this;
        };

        this.getCollection = function() {
            return collection;
        };
        this.isEmpty = function() {
            return empty;
        };
    }

    return HistoryCollectionDTO;
});