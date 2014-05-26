define(['App/App', 'Corp/Context/Context', 'Corp/File/FileDataObject'], function (app, Context, FileDataObject) {
    "use strict";

    var Service = app.service('ViewFileService', ['$http', function ($http) {
        /*
         * @param context Corp/Context/Context
         * @param file Corp/File/FileDataObject
         * @param callback callable
         */
        this.generate = function (context, file, callback) {

            if ((context instanceof Context) === false) {
                throw new Error("Context muse be instance of Context");
            }

            if ((file instanceof FileDataObject) === false) {
                throw new Error("file muse be instance of FileDataObject");
            }

            var datas =  $.param({
                backend      : context.getBackend(),
                metadata     : context.getMetadata(),
                generator    : context.getGenerator(),
                skeletonPath : file.getSkeletonPath(),
                file         : file.getTemplate(),
            }); + '&' + $.param(context.getQuestion());

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers : {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method  : "POST",
                    url     : __BASEPATH__ + "view-file",
                    data    : datas
                }
            ).success(function (data) {
                callback(data);
            }).error(function (data) {
                callback(data);
            });
        };
    }]);

    return Service;
});