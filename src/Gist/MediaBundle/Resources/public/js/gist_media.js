function setup_image_upload(config) {
    var template = $(config.preview).html();

    $(config.dropzone).dropzone({
        url: config.url,
        previewTemplate: template,
        previewsContainer: config.preview_container,
        maxFilesize: 2,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        parallelUploads: 5,
        clickable: config.clickable,
        acceptedFiles: config.accepted_files,
        init: function() {
            this.on('success', function(file, res) {
                console.log(res);

                // image modal update
                // $(file.previewElement).find('.dzp-image').attr('href', res.url);
                // console.log($('#main-image'));
                $(config.display_image).attr('src', res.url);
                $(config.display_image).data('id', res.id);
                $(config.form_field).val(res.id);
                $(config.display_image).parent().attr('href', res.url);
                // console.log(object_images);
            });

            this.on('complete', function(file) {
                // $(file.previewElement).find('.dzp-progress').remove();
                $(config.preview_container).html('');
            });

            this.on("error", function(file, message) { 
                alert(message);
                this.removeFile(file); 
            });
        }
    });
}



