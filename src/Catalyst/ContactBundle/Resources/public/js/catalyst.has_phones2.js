$(document).ready(function() {
    $('#add-phone-submit').click(function() {
        var url = $('#add-phone-form').attr('action');
        var data = $('#add-phone-form').serializeArray();
        var contact_person_id = $('#cform-hidden_person_id').val();
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
                        type  : data.data.type,
                        number: data.data.number,
                        id    : data.data.id,
                        contact_person_id    : contact_person_id,
                    });

                var contact_class = "." + $('#cform-unique-class').val();

                // copy over data and display
                $(contact_class).append(rendered);
                // hide modal
                $('#add-phone-modal').modal('hide');
            },
            error: function(xhr, text_status, error) {
            }
        });
    });
});
