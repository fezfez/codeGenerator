/**
 * Create a new Directory Object
 * @param name
 * @returns directory
 */
function FileDataObject() {
    "use strict";

    var name         = null,
        template     = null,
        skeletonPath = null,
        originalName = null;

    this.getName = function () {
        return name;
    };
    this.getTemplate = function () {
        return template;
    };
    this.getSkeletonPath = function () {
        return skeletonPath;
    };
    this.getOriginalName = function () {
        return originalName;
    };

    this.setName = function (value) {
        name = value;
        return this;
    };
    this.setTemplate = function (value) {
        template = value;
        return this;
    };
    this.setSkeletonPath = function (value) {
    	skeletonPath = value;
        return this;
    };
    this.setOriginalName = function (value) {
    	originalName = value;
        return this;
    };
}