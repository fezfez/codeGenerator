define(function() {
    /**
     * DirectoryDAO
     */
    function DirectoryDAO() {
        'use strict';

        /*
         * Find a directory in array of children
         * @param string directoryName
         * @param array children
         */
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
    }

    return DirectoryDAO;
});