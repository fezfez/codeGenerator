GeneratorApp.factory('GeneratorService', ['$http', '$rootScope', function($http, $rootScope) {

	var createDirectory = function(name) {
		var directory = {};
		directory.files = [];
		directory.name = name;
		directory.children = [];
		
		return directory;
	};
	
    var doRequest = function(datas, updateQuestion) {
    	$http(
            {
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
	            method: "POST",
	    	    url: "generator",
	    	    data: datas
            }
        ).success(function( data ) {
        	var filtredName = null,
        	    fileName = null;

    	    var diretoryBuilder = function(file, directories) {
    	    	filtredName        = file.fileName.split('/').filter(function(n){
    	    		return n;
    	    	});
    	    	fileName = filtredName[0];

    	    	if (filtredName.length === 1) {
    	    		directories.files.push({
    	            	'name'         : fileName,
    	            	'template'     : file.name,
    	            	'skeletonPath' : file.skeletonPath,
    	            });

    	    		return directories;
    	    	} else {
    	    		var create = true;
    	    		directories.children.forEach(function(child) {
    	    			if (child.name === fileName) {
    	    				create = false;
    	    			}
    	    		});
    	    		if (create === true) {
    	    			directories.children.push(createDirectory(fileName));
    	    		}
    	    		file.fileName = filtredName.slice(1).join('/');

      	    		directories.children.forEach(function(child) {
    	    			if (child.name === fileName) {
    	    				child = diretoryBuilder(file, child);
    	    				return ;
    	    			}
    	    		});
    	    		return directories;
    	    	}
    	    };
    	    
    	    var directories = createDirectory('test');

    	    $.each(data.generator.files, function(id, file) {
    	    	directories = diretoryBuilder(file, directories);
    	    });
    	    
    	    console.log(directories);
    	    

    	    $rootScope.fileList = directories;
    	    if (updateQuestion === true) {
    	    	$rootScope.questionsList = data.generator.questions;
    	    }
        });
    };
    return {
      events: function(datas, updateQuestion) { return doRequest(datas, updateQuestion); }
    };
}]);