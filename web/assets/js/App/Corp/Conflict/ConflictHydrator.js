define(function() {
    function ConflictHydrator() {

    }

    ConflictHydrator.prototype.hydrate = function(datas) {
        var firstChar    = null,
            items        = null,
            conflictList = [],
            conflictDTO  = {},
            lineDTO      = {};

        $.each(datas, function (fileName, data) {
            if (data.isConflict === true) {
                conflictDTO          = {};
                conflictDTO.fileName = fileName;
                conflictDTO.line     = [];
                items                = data.diff.split('\n');

                items.forEach(function(line) {
                    lineDTO         = {};
                    lineDTO.content = line;
                    firstChar       = line.substr(0, 1);

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

        return conflictList;
    };

    return ConflictHydrator;
});