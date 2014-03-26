require(["Angular", "App/App"], function (angular, GeneratorApp) {
    GeneratorApp.directive('fileTree', function () {
        return {
            templateUrl: __BASEPATH__ + 'assets/js/App/Template/file.html',
            replace: true,
            restrict: 'E',
            scope: {
                tree: '=',
                fileView: '&'
            },
        };
    });
});