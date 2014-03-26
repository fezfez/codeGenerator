require(['Corp/Directory/DirectoryBuilderFactory', 'Corp/Directory/DirectoryBuilder'], function(DirectoryBuilderFactory, DirectoryBuilder) {

    describe('Unit: Check method getInstance', function() {

        it('Should return a DirectoryBuilder instance', function() {

        	expect(true).toEqual(DirectoryBuilderFactory.getInstance() instanceof DirectoryBuilder);
        	expect(true).toEqual(DirectoryBuilderFactory.getInstance() instanceof DirectoryBuilder);
        });
    });
});