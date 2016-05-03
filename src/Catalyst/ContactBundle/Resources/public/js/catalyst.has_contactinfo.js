$(document).ready(function() {
    $('#cform-contact_type').on('change',function(){
        var selected = $(this).find(":selected").text();
        console.log(selected);
        if(selected === 'Company'){
            $('#contact-individual').addClass('hidden');
            $('#contact-company').removeClass('hidden');
        }else{
            $('#contact-individual').removeClass('hidden');
            $('#contact-company').addClass('hidden');
        }
    });
});
