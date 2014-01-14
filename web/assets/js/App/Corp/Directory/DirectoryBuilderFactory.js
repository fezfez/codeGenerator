var DirectoryBuilderFactory = (function () {
    "use strict";

    var instance = null;

    function init() {
        return new DirectoryBuilder(new DirectoryDAO());
    }

    return {
        getInstance: function () {
            if (instance === null) {
                instance = init();
            }
            return instance;
        }
    };
})();