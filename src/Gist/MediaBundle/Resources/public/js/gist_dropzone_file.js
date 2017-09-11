function setup_file_upload2(config) {
    var template = $(config.preview).html();
    $(config.dropzone).dropzone({
        url: config.url,
        previewTemplate: template,
        previewsContainer: config.preview_container,
        maxFilesize: 2,
        thumbnailWidth: 150,
        parallelUploads: 5,
        thumbnailHeight: 150,
        clickable: config.clickable,
        acceptedFiles: config.accepted_files,
        init: function() {
            this.on('success', function(file, res) {
                console.log(res);
                console.log(res.filetype);
                console.log(config.accepted_files);
                $(config.display_image).data('id', res.id);
                $(config.display_image).removeClass('fa-file-excel-o fa-file-word-o fa-file-powerpoint-o fa-file-pdf-o');
                switch(res.filetype)
                {
                    case 'xls':
                    case 'xlsx': $(config.display_image).addClass('fa-file-excel-o');
                                break;
                    case 'odt':
                    case 'doc':
                    case 'docx': $(config.display_image).addClass('fa-file-word-o');
                                break;
                    case 'ppt':
                    case 'pptx': $(config.display_image).addClass('fa-file-powerpoint-o');
                                break;
                    case 'pdf': $(config.display_image).addClass('fa-file-pdf-o');
                                break;
                    default: $(config.display_image).attr('src', res.url);
                }
                $(config.form_field).val(res.id);
                $(config.display_image).parent().attr('href', res.url);
                // console.log(object_images);
            });
            this.on('complete', function(file) {
                // $(file.previewElement).find('.dzp-progress').remove();
                $(config.preview_container).html('');
            });

            this.on('error', function(file, message) { 
                alert(message);
                this.removeFile(file); 
            });
        }
    });
}