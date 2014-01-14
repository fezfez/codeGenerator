/**
 * Create a new Directory Object
 * @param name
 * @returns directory
 */
function DirectoryDataObject(directoryName) {
    "use strict";

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
        if ((child instanceof DirectoryDataObject) !== true) {
            throw new Error('Not instance of Directory');
        }
        children.push(child);

        return this;
    };
}