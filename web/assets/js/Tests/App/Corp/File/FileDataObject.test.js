require(['Corp/File/FileDataObject'], function(FileDataObject) {

    describe('Unit: Check getter setters', function() {

        it('Check name attribute', function() {
            var fileObject = new FileDataObject();
            fileObject.setName('myname');

            expect(fileObject.getName()).toEqual('myname');
        });
        
        it('Check template attribute', function() {
            var fileObject = new FileDataObject();
            fileObject.setTemplate('template');

            expect(fileObject.getTemplate()).toEqual('template');
        });
        
        it('Check skeletonPath attribute', function() {
            var fileObject = new FileDataObject();
            fileObject.setSkeletonPath('skeletonPath');

            expect(fileObject.getSkeletonPath()).toEqual('skeletonPath');
        });
        
        it('Check originalName attribute', function() {
            var fileObject = new FileDataObject();
            fileObject.setOriginalName('originalName');

            expect(fileObject.getOriginalName()).toEqual('originalName');
        });
        
        it('Check isWritable attribute', function() {
            var fileObject = new FileDataObject();
            fileObject.setIsWritable('skeletonPath');

            expect(fileObject.isWritable()).toEqual('skeletonPath');
        });
    });
});