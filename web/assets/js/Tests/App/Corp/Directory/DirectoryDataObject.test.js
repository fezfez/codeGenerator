require(['Corp/Directory/DirectoryDataObject', 'Corp/File/FileDataObject'], function(DirectoryDataObject, FileDataObject) {

    describe('Unit: Check getter setters DirectoryDataObject', function() {

        it('Check name attribute', function() {
            var directoryObject = new DirectoryDataObject('myname');

            expect(directoryObject.getName()).toEqual('myname');
        });

        it('Check children attribute', function() {
            var directoryObject = new DirectoryDataObject('myname');

            expect(directoryObject.getChildren().length).toEqual(0);

            directoryObject.addChildren(new DirectoryDataObject('test'));

            expect(directoryObject.getChildren().length).toEqual(1);
        });

        it('Check file attribute', function() {
            var directoryObject = new DirectoryDataObject('myname');

            expect(directoryObject.getFiles().length).toEqual(0);

            directoryObject.addFile(new FileDataObject());

            expect(directoryObject.getFiles().length).toEqual(1);
        });
    });
});