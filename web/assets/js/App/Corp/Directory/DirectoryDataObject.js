define(function() {
    /**
     * Create a new Directory Object
     * @param name
     * @returns DirectoryDataObject
     */
    function DirectoryDataObject(directoryName) {
        'use strict';

        var name     = directoryName,
            files    = [],
            children = [];

        this.getName = function () {
            return name;
        };
        this.getFiles = function () {
            return files;
        };
        this.getChildren = function () {
            return children;
        };

        this.addFile = function (file) {
            files.push(file);
        };
        this.addChildren = function (child) {
            children.push(child);
            return this;
        };
    }

    return DirectoryDataObject;
});