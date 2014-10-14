define(function() {

    function GeneratorCollectionDTO() {
        var collection = null;

        this.setCollection = function(value) {
            collection = value;
            return this;
        };

        this.getCollection = function() {
            return collection;
        };
        this.isEmpty = function() {
            return (collection === null) ? true : false;
        };
    }

    return GeneratorCollectionDTO;
});