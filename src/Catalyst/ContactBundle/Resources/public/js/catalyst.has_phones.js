$(document).ready(function() {
    $('#add-phone-submit').click(function() {
        var url = $('#add-phone-form').attr('action');
        var data = $('#add-phone-form').serializeArray();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(data, text_status, xhr) {
                // make phone node
                var template = $('#phone-template').html();
                Mustache.parse(template);   // optional, speeds up future uses
                var rendered = Mustache.render(template, 
                    {
                        type  :     data.data.name,
                        number:     data.data.number,
                        id    :     data.data.id,
                        is_primary: data.data.is_primary
                    });

                // copy over data and display
                $('#phone-section').append(rendered);
                // hide modal
                $('#hris_workforce_employeeform-number').val('');
                $('#add-phone-modal').modal('hide');
            },
            error: function(xhr, text_status, error) {
            }
        });
    });
});
