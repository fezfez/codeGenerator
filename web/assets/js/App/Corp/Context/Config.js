define(function() {
    /**
     * Create a new Directory Object
     * @param name
     * @returns DirectoryDataObject
     */
    function Config() {
        "use strict";

        var adapter     = null,
            question    = {};

        this.setAdapter = function (value) {
            adapter = value;
            return this;
        };
        this.setMetadata = function (value) {
            metadata = value;
            return this;
        };
        this.setQuestion = function (attribute, value) {
            question[attribute] = value;
            return this;
        };
        this.getAdapter = function () {
            return adapter;
        };
        this.getQuestion = function () {
            return question;
        };
    }

    return Config;
});