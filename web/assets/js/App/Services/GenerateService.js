GeneratorApp.factory('GenerateService', ['$http', function ($http) {
    "use strict";

    return {
        generate : function (datas, callback) {
            $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                    method: "POST",
                    url: __BASEPATH__ + "generate",
                    data: datas
                }
            ).success(function (datas) {
                var firstChar = null,
                    items = null,
                    conflictList = null,
                    log = null,
                    conflictDTO = {},
                    lineDTO = {};
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
            	alert(data.error);
            });
        }
    };
}]);