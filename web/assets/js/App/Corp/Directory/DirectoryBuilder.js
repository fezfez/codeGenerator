define(['Corp/File/FileDataObject', 'Corp/Directory/DirectoryDataObject'], function(FileDataObject, DirectoryDataObject) {
    /**
     * DirectoryBuilder instance
     * @param DirectoryDAO
     * @returns DirectoryBuilder
     */
    function DirectoryBuilder(DirectoryDAO) {
        'use strict';

        var DAO = DirectoryDAO;

        /**
         * Build rectories recursivly
         * @param file
         * @param directories
         * @returns directories
         */
        this.build = function (file, directories) {
            var filtredName = file.fileName.split('/').filter(function (n) {
                    return n;
                }),
                fileName = filtredName[0],
                directoryName = filtredName[0],
                child = null,
                fileDTO = null;

            if (undefined === file.originalName) {
                file.originalName = file.fileName;
            }

            // If this is a file
            if (filtredName.length === 1) {
                // Add to the current directory
                fileDTO = new FileDataObject();
                fileDTO.setName(fileName)
                    .setTemplate(file.name)
                    .setSkeletonPath(file.skeletonPath)
                    .setOriginalName(file.originalName)
                    .setIsWritable(file.isWritable);

                directories.addFile(fileDTO);

                return directories;
            }

            try {
                // Try to find the directory in child
                child = DAO.findChildByNameInChildren(directoryName, directories.getChildren());
            } catch (e) {
                // directory not found, create and retrieve
                directories.addChildren(new DirectoryDataObject(directoryName));
                child = DAO.findChildByNameInChildren(directoryName, directories.getChildren());
            }

            // We advance 
            file.fileName = filtredName.slice(1).join('/');
            child         = this.build(file, child);

            return directories;
        };
    }

    return DirectoryBuilder;
});