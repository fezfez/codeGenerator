define(function() {

    function HistoryDTO() {
        var id    = null,
            label = null;

        this.setId = function(value) {
            id = value;
            return this;
        };
        this.setLabel = function(value) {
            label = value;
            return this;
        };

        this.getId = function() {
            return id;
        };
        this.getLabel = function() {
            return label;
        };
    }

    return HistoryDTO;
});