$(document).ready(function() {
    $('.phone-button').live('click', function()
    {
        // hidden input for class name
        var hidden_input = $(this).closest('.contact-class-outer').find('.contact-class').val();
        $('#cform-unique-class').val(hidden_input);

        // hidden input for contact person id
        var hidden_input2 = $(this).closest('.contact-class-outer').find('.contact-person-id').val();
        $('#cform-hidden_person_id').val(hidden_input2);
    });

    $('#add-contact-person-submit').click(function() {

        // counting contact person class
        var contact_person_counter = 1;
        $(".contact-class-outer").each(function() 
        {
            contact_person_counter++;
        });

        var url = $('#add-contact-person-form').attr('action');
        var data = $('#add-contact-person-form').serializeArray();
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(data, text_status, xhr) {

                // make address node
                var template = $('#contact-person-template').html();
                Mustache.parse(template);   // optional, speeds up future uses
                var rendered = Mustache.render(template, 
                    {
                        id    : data.data.id,
                        first_name  : data.data.first_name,
                        middle_name: data.data.middle_name,
                        last_name  : data.data.last_name,
                        email : data.data.email,
                        checked    : data.data.checked,
                        counter    : contact_person_counter,
                    });
                $('#contact-complete').append(rendered);
                $('#add-contact-person').modal('hide');

                // changing class of div
                $(".contact-class-" + contact_person_counter).closest('.contact').attr('class','contact' + contact_person_counter);
            },
            error: function(xhr, text_status, error) {
            }
        });
    });

});


