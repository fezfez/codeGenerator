/**
 * Create a new Directory Object
 * @param name
 * @returns directory
 */
function DirectoryDAO() {
	"use strict";

    this.findChildByNameInChildren = function (directoryName, children) {
        var foundedChild = null;

        children.forEach(function (child) {
            if (child.getName() === directoryName) {
                foundedChild = child;
                return;
            }
        });

        if (null !== foundedChild) {
            return foundedChild;
        }

        throw new Error('Cant find child with name "' + directoryName + '"');
    };

    this.viewFile = function (datas, callback) {
        $http(
            {
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                method: "POST",
                url: "view-file",
                data: datas
            }
        ).success(function (data) {
            callback(data);
        });
    };
}