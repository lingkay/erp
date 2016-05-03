$(document).ready(function() {
    $('#add-address-submit').click(function() {
        var url = $('#add-address-form').attr('action');
        var data = $('#add-address-form').serializeArray();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(data, text_status, xhr) {
                // make address node
                var template = $('#address-template').html();
                Mustache.parse(template);   // optional, speeds up future uses
                var rendered = Mustache.render(template, 
                    {
                        id    : data.data.id,
                        name  : data.data.name,
                        street: data.data.street,
                        city  : data.data.city,
                        state : data.data.state,
                        country    : data.data.country,
                        longitude    : data.data.longitude,
                        latitude    : data.data.latitude
                    });

                // copy over data and display
                $('#address-section').append(rendered);
                // hide modal
                $('#add-address-modal').modal('hide');
            },
            error: function(xhr, text_status, error) {
            }
        });
    });

});
