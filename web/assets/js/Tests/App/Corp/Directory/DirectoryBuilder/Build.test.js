require(['Corp/Directory/DirectoryBuilderFactory', 'Corp/Directory/DirectoryDataObject'], function(DirectoryBuilderFactory, DirectoryDataObject) {

    describe('Unit: Check method build', function() {

        it('Should return a DirectoryDataObject instance', function() {

            var directoryBuilder = DirectoryBuilderFactory.getInstance();

            directoryBuilder.build({
                name : 'test',
                fileName : 'test',
                skeletonPath :'test',
                originalName : 'test',
                isWritable : false,
            }, new DirectoryDataObject(''));
        });

        it('Should return a DirectoryDataObject instance with original filename', function() {

            var directoryBuilder = DirectoryBuilderFactory.getInstance();

            directoryBuilder.build({
                name : 'test',
                fileName : 'test/test',
                originalName : 'test2',
                skeletonPath :'test',
                isWritable : false,
            }, new DirectoryDataObject(''));
        });
    });
});