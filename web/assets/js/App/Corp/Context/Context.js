define(function() {
    /**
     * Create a new Directory Object
     * @param name
     * @returns DirectoryDataObject
     */
    function Context() {
        "use strict";

        var backend     = null,
            metadata    = null,
            generator   = null,
            question    = {};

        this.setBackend = function (value) {
        	backend = value;
            return this;
        };
        this.setMetadata = function (value) {
        	metadata = value;
            return this;
        };
        this.setGenerator = function (value) {
        	generator = value;
            return this;
        };
        this.setQuestion = function (attribute, value) {
        	question[attribute] = value;
            return this;
        };
        this.getBackend = function () {
            return backend;
        };
        this.getMetadata = function () {
            return metadata;
        };
        this.getGenerator = function () {
            return generator;
        };
        this.getQuestion = function () {
            return question;
        };
    }

    return Context;
});