// check if field is blank
function isFieldBlank(name)
{
    if($('[name=' + name + ']').val() != '' || $('[name=' + name + ']').html() != '')
        return true;
    
    return false;
}

// check if field is null
function isFieldNull(name)
{
    if($('[name=' + name + ']').val() != 0 || $('[name=' + name + ']').html() != 0)
        return true;
    
    return false;
}

// check two fields if they are equal or not
function isFieldsEqual(name1, name2)
{
    if(($('[name=' + name1 + ']').val() != $('[name=' + name2 + ']').val()) || ($('[name=' + name1 + ']').html() != $('[name=' + name2 + ']').html()))
        return false;

    return true;
}

// set message for validations, code inside can be changed to set how the message will be viewed
function validationMessage(message)
{
    // new messages will replace previous message
    $('.dropdown-menu-list').html('<li><a href="#"><span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>' + message + '</a></li>')
    $('#header_notification_bar').addClass('open');
}

function checkMessage(name, label, message)
{
    // default message
    if(message == undefined)
        validationMessage('Cannot leave ' + (label == undefined ? name : label) + ' blank.');
    else
        validationMessage(message);
}

// validate functions, default message is set when message is null
// validate field using blur event, specify method e.g. blank, etc(will add more if needed)
function validateOnBlur(name, method, label, message)
{
    $('[name=' + name + ']').blur(function(){
        if(method == 'blank')
        {
            if(!isFieldBlank(name))
            {
                checkMessage(name, label, message);
            }
        }
        else if(method == 'null')
        {
            if(!isFieldNull(name))
            {
                checkMessage(name, label, message);
            }
        }
    });
}