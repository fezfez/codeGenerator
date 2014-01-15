GeneratorApp.factory('GeneratorService', ['$http', '$q', function ($http, $q) {
    "use strict";
    
    return {
        http : false,
        build : function (datas, callback) {
            var canceler = $q.defer(),
                self = this;

            if (self.http === true) {
                //canceler.resolve();
            }

            self.http = true;
            $http(
                {
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    method: "POST",
                    url: "generator",
                    data: datas,
                    timeout: canceler.promise
                }
            ).success(function (data) {
                var directories      = new DirectoryDataObject(''),
                    DirectoryBuilder = DirectoryBuilderFactory.getInstance();

                $.each(data.generator.files, function (id, file) {
                    directories = DirectoryBuilder.build(file, directories);
                });

                callback(directories, data.generator.questions);
                self.http = false;
            });
        }
    };
}]);