define(["App/App", "Corp/Context/Context"], function(app, Context) {
    "use strict";

    var Service = app.service('PreviewService', ['$http', function ($http) {
        /*
         * Generate files
         * @param context Context
         * @param callback callable
         */
        this.generate = function (context, callback) {

            if ((context instanceof Context) === false) {
            	throw new Error("Context muse be instance of Context");
            }

            var datas =  $.param({
                backend   : context.getBackend(),
                metadata  : context.getMetadata(),
                generator : context.getGenerator(),
                questions : context.getQuestion(),
                conflict  : $('.conflict_handle').serialize()
            });

            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method: "POST",
                    url: __BASEPATH__ + "generate",
                    data: datas
                }
            ).success(function (datas) {
                var firstChar    = null,
                    items        = null,
                    conflictList = null,
                    log          = null,
                    conflictDTO  = {},
                    lineDTO      = {};

                if (datas.conflict !== undefined) {
                    conflictList = [];
                    $.each(datas.conflict, function (fileName, data) {
                        if (data.isConflict === true) {
                            conflictDTO = {};
                            conflictDTO.fileName = fileName;
                            conflictDTO.line = [];
                            items = data.diff.split("\n");
                            items.forEach(function(line) {
                                lineDTO = {};
                                lineDTO.content = line;
                                firstChar = line.substr(0, 1);
                                if (firstChar === '-') {
                                    lineDTO.type = 'remove';
                                } else if (firstChar === '+') {
                                    lineDTO.type = 'add';
                                } else {
                                    lineDTO.type = 'other';
                                }
                                conflictDTO.line.push(lineDTO);
                            });
                            conflictList.push(conflictDTO);
                        }
                    });
                } else {
                    log = datas.generationLog;
                }

                callback({'log' : log, 'conflictList' : conflictList});
            }).error(function(data) {
                callback({'error' : data.error});
            });
        };
    }]);

    return Service;
});