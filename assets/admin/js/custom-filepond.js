"use strict";

/*== Filepond ==*/
$.fn.filepond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateSize, FilePondPluginFileValidateType);
$(document).ready(function () {
    let maxFiles = 1, filesize = '', fileType = '', oldRawFiles = '', oldFiles = [];
    maxFiles = $('.filepond').data('maxfiles');
    filesize = $('.filepond').data('filesize');
    fileType = $('.filepond').data('filetype');
    oldRawFiles = $('.filepond').data('oldfiles');
    if (oldRawFiles) {
        oldRawFiles = oldRawFiles.split(",");
        for (let i = 0; i < oldRawFiles.length; i++) {
            oldFiles.push({source: oldRawFiles[i]});
        }
    }

    $('.filepond').filepond({
        allowFileTypeValidation: true,
        acceptedFileTypes: fileType,
        allowMultiple: maxFiles > 1 ? true : false,
        maxFiles: maxFiles,
        allowFileSizeValidation: filesize ? true : false,
        maxFileSize: filesize,
        labelMaxFileSizeExceeded: 'File is too large',
        allowReorder: true,
        allowRemove: true,
        // disabled: true,
        files: oldFiles
    });

    let fileSelectors = document.querySelectorAll('.filepond ~ .filepond-files');
    for (let i = 0; i < fileSelectors.length; i++) {
        $('.filepond').on('FilePond:updatefiles', function (e) {
            $('.filepond--data').remove();
            const files = $(this).filepond('getFiles');
            let dt = new DataTransfer();
            $(files).each(function (index) {
                if (files[index].file instanceof File) {
                    dt.items.add(files[index].file);
                } else if (files[index].file instanceof Blob) {
                    dt.items.add(new File([files[index].file], files[index].file.name, {type: files[index].file.type}));
                }
            });
            fileSelectors[i].files = dt.files;
        });
    }
});



