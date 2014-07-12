require(['Corp/Directory/DirectoryDAO', 'Corp/Directory/DirectoryDataObject'], function(DirectoryDAO, DirectoryDataObject) {

    describe('Unit: Check method findChildByNameInChildren', function() {

        it('Should return a directory with name test', function() {
            var directoryDAO = new DirectoryDAO(),
                directoryCollection = new Array(new DirectoryDataObject('test'), new DirectoryDataObject('mydir'));

            var directoryFound = directoryDAO.findChildByNameInChildren('test', directoryCollection);
            expect(directoryFound.getName()).toEqual('test');
        });

        it('Should throw exception cause directory nod found', function() {
            var directoryDAO = new DirectoryDAO(),
                directoryCollection = new Array(new DirectoryDataObject('test'), new DirectoryDataObject('mydir'));

            try {
                directoryDAO.findChildByNameInChildren('fail', directoryCollection);
                expect('error if pass here').toEqual('test');
            } catch(e) {
                expect(true).toEqual(e instanceof Error);
            }
        });
    });
});