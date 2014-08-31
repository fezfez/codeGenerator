define(function() {
    /**
     * Create a new Context Object
     * @param name
     * @returns DirectoryDataObject
     */
    function Context() {
        "use strict";

        var backend               = null,
            metadata              = null,
            generator             = null,
            history               = null,
            question              = {},
            directories           = null,
            backendCollection     = null,
            metadataCollection    = null,
            generatorCollection   = null,
            searchGeneratorCollection   = null,
            historyCollection     = null,
            questionCollection    = null;


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
        this.setHistory = function (value) {
            history = value;
            return this;
        };
        this.setBackendCollection = function (value) {
            backendCollection  = value;
            return this;
        };
        this.setMetadataCollection  = function (value) {
            metadataCollection  = value;
            return this;
        };
        this.setGeneratorCollection  = function (value) {
            generatorCollection = value;
            return this;
        };
        this.setSearchGeneratorCollection  = function (value) {
            searchGeneratorCollection = value;
            return this;
        };
        this.setHistoryCollection  = function (value) {
            historyCollection = value;
            return this;
        };
        this.setQuestionCollection  = function (value) {
            questionCollection = value;
            return this;
        };
        this.setDirectories  = function (value) {
            directories  = value;
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
        this.getHistory = function () {
            return history;
        };
        this.getDirectories = function () {
            return directories;
        };
        this.getBackendCollection = function () {
            return backendCollection;
        };
        this.getMetadataCollection = function () {
            return metadataCollection;
        };
        this.getGeneratorCollection = function () {
            return generatorCollection;
        };
        this.getSearchGeneratorCollection = function () {
            return searchGeneratorCollection;
        };
        this.getHistoryCollection = function () {
            return historyCollection;
        };
        this.getQuestionCollection = function () {
            return questionCollection;
        };
        this.getQuestion = function () {
            return question;
        };
    }

    return Context;
});