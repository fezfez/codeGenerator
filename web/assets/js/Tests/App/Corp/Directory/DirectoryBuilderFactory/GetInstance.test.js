require(['Corp/Directory/DirectoryBuilderFactory', 'Corp/Directory/DirectoryBuilder'], function(DirectoryBuilderFactory, DirectoryBuilder) {

    describe('Unit: Check method getInstance', function() {
        it('Should return a DirectoryBuilder instance', function() {

            var directoryBuilder = DirectoryBuilderFactory.getInstance(),
                directoryBuilderNewInstance = DirectoryBuilderFactory.getInstance();

            expect(true).toEqual(directoryBuilder instanceof DirectoryBuilder);
            expect(directoryBuilderNewInstance).toEqual(directoryBuilder);
        });
    });
});