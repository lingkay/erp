<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('eo', array (
  'FOSUserBundle' => 
  array (
    'group.edit.submit' => 'Aktualigi',
    'group.show.name' => 'Grupnomo',
    'group.new.submit' => 'Krei grupon',
    'group.flash.updated' => 'La grupo estas aktualigita.',
    'group.flash.created' => 'La grupo estas kreita.',
    'group.flash.deleted' => 'La grupo estas forigita.',
    'security.login.username' => 'Uzantnomo',
    'security.login.password' => 'Pasvorto',
    'security.login.remember_me' => 'Memoru min',
    'security.login.submit' => 'Ensaluti',
    'profile.show.username' => 'Uzantnomo',
    'profile.show.email' => 'Retpoŝtadreso',
    'profile.edit.submit' => 'Aktualigi',
    'profile.flash.updated' => 'La profilo estas aktualigita.',
    'change_password.submit' => 'Ŝanĝi la pasvorton',
    'change_password.flash.success' => 'La pasvorto estas ŝanĝita',
    'registration.check_email' => 'Por fini la registriĝon bonvolu alklaki la ligilon en la retpoŝtmesaĝo, kiun ni ĵus sendis al vi. Se vi ne ricevis la mesaĝon, kontrolu vian spam-dosierujon.
',
    'registration.confirmed' => 'Gratulon %username%, via konto estas aktiva.',
    'registration.back' => 'Reiri al la origina paĝo.',
    'registration.submit' => 'Registriĝi',
    'registration.flash.user_created' => 'La uzanto estis kreita.',
    'registration.email.subject' => 'Bonvenon %username%!',
    'registration.email.message' => 'Saluton %username%!

Por kompletigi la aktivigadon de via konto - Bonvolu viziti %confirmationUrl%

Ĉi tiu ligilo povas validigi nur vian konton.

Amike,
la Teamo.
',
    'resetting.check_email' => 'Ni sendis retpoŝtmesaĝon al vi. Ĝi enhavas ligilon, kiun vi devas alklaki por ŝanĝi vian pasvorton.
Rimarku: Vi nur povas uzi la ligilon dum %tokenLifetime% horoj.

Se vi ne ricevis la mesaĝon, kontrolu vian spam-dosierujon.
',
    'resetting.request.username' => 'Uzantnomo aŭ retpoŝtadreso',
    'resetting.request.submit' => 'Ŝanĝi la Pasvorton',
    'resetting.reset.submit' => 'Ŝanĝi la Pasvorton',
    'resetting.flash.success' => 'La pasvorto estas sukcese ŝanĝita.',
    'resetting.email.subject' => 'Ŝanĝi la pasvorton',
    'resetting.email.message' => 'Saluton %username%!

Por ŝanĝi la pasvorton - Bonvolu viziti %confirmationUrl%

Amike,
la Teamo.
',
    'layout.logout' => 'Elsaluti',
    'layout.login' => 'Ensaluti',
    'layout.register' => 'Registriĝi',
    'layout.logged_in_as' => 'Ensalutita kiel %username%',
    'form.group_name' => 'Grupnomo',
    'form.username' => 'Uzantnomo',
    'form.email' => 'Retpoŝtadreso',
    'form.current_password' => 'Nuna pasvorto',
    'form.password' => 'Pasvorto',
    'form.password_confirmation' => 'Retajpu pasvorton',
    'form.new_password' => 'Nova pasvorto',
    'form.new_password_confirmation' => 'Retajpu novan pasvorton',
  ),
  'validators' => 
  array (
    'fos_user.username.already_used' => 'Tiu ĉi uzantnomo estas jam okupita.',
    'fos_user.username.blank' => 'Enmetu vian uzantnomo.',
    'fos_user.username.short' => 'La uzantnomo estas tro mallonga.',
    'fos_user.username.long' => 'La uzantnomo estas tro longa.',
    'fos_user.email.already_used' => 'Tiu ĉi retpoŝtadreso jam estas registrita.',
    'fos_user.email.blank' => 'Enmetu validan retpoŝtan adreson.',
    'fos_user.email.short' => 'La retpoŝtadreso estas tro mallonga.',
    'fos_user.email.long' => 'La retpoŝtadreso estas tro longa.',
    'fos_user.email.invalid' => 'La retpoŝtadreso ne estas valida.',
    'fos_user.password.blank' => 'Enmetu pasvorton.',
    'fos_user.password.short' => 'La pasvorto estas tro mallonga.',
    'fos_user.password.mismatch' => 'La pasvortoj ne samas.',
    'fos_user.new_password.blank' => 'Bonvolu enmeti novan pasvorton.',
    'fos_user.new_password.short' => 'La nova pasvorto estas tro mallonga.',
    'fos_user.current_password.invalid' => 'La pasvorto estas nevalida.',
    'fos_user.group.blank' => 'Bonvolu enmeti grupnomon.',
    'fos_user.group.short' => 'La nomo estas tro mallonga.',
    'fos_user.group.long' => 'La nomo estas tro longa.',
    'fos_group.name.already_used' => 'La grupnomon estas jam okupita.',
  ),
));

$catalogueEn = new MessageCatalogue('en', array (
  'validators' => 
  array (
    'This value should be false.' => 'This value should be false.',
    'This value should be true.' => 'This value should be true.',
    'This value should be of type {{ type }}.' => 'This value should be of type {{ type }}.',
    'This value should be blank.' => 'This value should be blank.',
    'The value you selected is not a valid choice.' => 'The value you selected is not a valid choice.',
    'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.' => 'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.',
    'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.' => 'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.',
    'One or more of the given values is invalid.' => 'One or more of the given values is invalid.',
    'This field was not expected.' => 'This field was not expected.',
    'This field is missing.' => 'This field is missing.',
    'This value is not a valid date.' => 'This value is not a valid date.',
    'This value is not a valid datetime.' => 'This value is not a valid datetime.',
    'This value is not a valid email address.' => 'This value is not a valid email address.',
    'The file could not be found.' => 'The file could not be found.',
    'The file is not readable.' => 'The file is not readable.',
    'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.' => 'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
    'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.' => 'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.',
    'This value should be {{ limit }} or less.' => 'This value should be {{ limit }} or less.',
    'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.' => 'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.',
    'This value should be {{ limit }} or more.' => 'This value should be {{ limit }} or more.',
    'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.' => 'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.',
    'This value should not be blank.' => 'This value should not be blank.',
    'This value should not be null.' => 'This value should not be null.',
    'This value should be null.' => 'This value should be null.',
    'This value is not valid.' => 'This value is not valid.',
    'This value is not a valid time.' => 'This value is not a valid time.',
    'This value is not a valid URL.' => 'This value is not a valid URL.',
    'The two values should be equal.' => 'The two values should be equal.',
    'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.' => 'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.',
    'The file is too large.' => 'The file is too large.',
    'The file could not be uploaded.' => 'The file could not be uploaded.',
    'This value should be a valid number.' => 'This value should be a valid number.',
    'This file is not a valid image.' => 'This file is not a valid image.',
    'This is not a valid IP address.' => 'This is not a valid IP address.',
    'This value is not a valid language.' => 'This value is not a valid language.',
    'This value is not a valid locale.' => 'This value is not a valid locale.',
    'This value is not a valid country.' => 'This value is not a valid country.',
    'This value is already used.' => 'This value is already used.',
    'The size of the image could not be detected.' => 'The size of the image could not be detected.',
    'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.' => 'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.',
    'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.' => 'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.',
    'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.' => 'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.',
    'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.' => 'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.',
    'This value should be the user\'s current password.' => 'This value should be the user\'s current password.',
    'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.' => 'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.',
    'The file was only partially uploaded.' => 'The file was only partially uploaded.',
    'No file was uploaded.' => 'No file was uploaded.',
    'No temporary folder was configured in php.ini.' => 'No temporary folder was configured in php.ini, or the configured folder does not exist.',
    'Cannot write temporary file to disk.' => 'Cannot write temporary file to disk.',
    'A PHP extension caused the upload to fail.' => 'A PHP extension caused the upload to fail.',
    'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.' => 'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.',
    'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.' => 'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.',
    'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.' => 'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.',
    'Invalid card number.' => 'Invalid card number.',
    'Unsupported card type or invalid card number.' => 'Unsupported card type or invalid card number.',
    'This is not a valid International Bank Account Number (IBAN).' => 'This is not a valid International Bank Account Number (IBAN).',
    'This value is not a valid ISBN-10.' => 'This value is not a valid ISBN-10.',
    'This value is not a valid ISBN-13.' => 'This value is not a valid ISBN-13.',
    'This value is neither a valid ISBN-10 nor a valid ISBN-13.' => 'This value is neither a valid ISBN-10 nor a valid ISBN-13.',
    'This value is not a valid ISSN.' => 'This value is not a valid ISSN.',
    'This value is not a valid currency.' => 'This value is not a valid currency.',
    'This value should be equal to {{ compared_value }}.' => 'This value should be equal to {{ compared_value }}.',
    'This value should be greater than {{ compared_value }}.' => 'This value should be greater than {{ compared_value }}.',
    'This value should be greater than or equal to {{ compared_value }}.' => 'This value should be greater than or equal to {{ compared_value }}.',
    'This value should be identical to {{ compared_value_type }} {{ compared_value }}.' => 'This value should be identical to {{ compared_value_type }} {{ compared_value }}.',
    'This value should be less than {{ compared_value }}.' => 'This value should be less than {{ compared_value }}.',
    'This value should be less than or equal to {{ compared_value }}.' => 'This value should be less than or equal to {{ compared_value }}.',
    'This value should not be equal to {{ compared_value }}.' => 'This value should not be equal to {{ compared_value }}.',
    'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.' => 'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.',
    'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.' => 'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.',
    'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.' => 'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.',
    'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.' => 'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.',
    'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.' => 'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.',
    'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.' => 'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.',
    'An empty file is not allowed.' => 'An empty file is not allowed.',
    'The host could not be resolved.' => 'The host could not be resolved.',
    'This value does not match the expected {{ charset }} charset.' => 'This value does not match the expected {{ charset }} charset.',
    'This is not a valid Business Identifier Code (BIC).' => 'This is not a valid Business Identifier Code (BIC).',
    'This form should not contain extra fields.' => 'This form should not contain extra fields.',
    'The uploaded file was too large. Please try to upload a smaller file.' => 'The uploaded file was too large. Please try to upload a smaller file.',
    'The CSRF token is invalid. Please try to resubmit the form.' => 'The CSRF token is invalid. Please try to resubmit the form.',
    'fos_user.username.already_used' => 'The username is already used.',
    'fos_user.username.blank' => 'Please enter a username.',
    'fos_user.username.short' => 'The username is too short.',
    'fos_user.username.long' => 'The username is too long.',
    'fos_user.email.already_used' => 'The email is already used.',
    'fos_user.email.blank' => 'Please enter an email.',
    'fos_user.email.short' => 'The email is too short.',
    'fos_user.email.long' => 'The email is too long.',
    'fos_user.email.invalid' => 'The email is not valid.',
    'fos_user.password.blank' => 'Please enter a password.',
    'fos_user.password.short' => 'The password is too short.',
    'fos_user.password.mismatch' => 'The entered passwords don\'t match.',
    'fos_user.new_password.blank' => 'Please enter a new password.',
    'fos_user.new_password.short' => 'The new password is too short.',
    'fos_user.current_password.invalid' => 'The entered password is invalid.',
    'fos_user.group.blank' => 'Please enter a name.',
    'fos_user.group.short' => 'The name is too short.',
    'fos_user.group.long' => 'The name is too long.',
    'fos_group.name.already_used' => 'The name is already used.',
  ),
  'security' => 
  array (
    'An authentication exception occurred.' => 'An authentication exception occurred.',
    'Authentication credentials could not be found.' => 'Authentication credentials could not be found.',
    'Authentication request could not be processed due to a system problem.' => 'Authentication request could not be processed due to a system problem.',
    'Invalid credentials.' => 'Invalid credentials.',
    'Cookie has already been used by someone else.' => 'Cookie has already been used by someone else.',
    'Not privileged to request the resource.' => 'Not privileged to request the resource.',
    'Invalid CSRF token.' => 'Invalid CSRF token.',
    'Digest nonce has expired.' => 'Digest nonce has expired.',
    'No authentication provider found to support the authentication token.' => 'No authentication provider found to support the authentication token.',
    'No session available, it either timed out or cookies are not enabled.' => 'No session available, it either timed out or cookies are not enabled.',
    'No token could be found.' => 'No token could be found.',
    'Username could not be found.' => 'Username could not be found.',
    'Account has expired.' => 'Account has expired.',
    'Credentials have expired.' => 'Credentials have expired.',
    'Account is disabled.' => 'Account is disabled.',
    'Account is locked.' => 'Account is locked.',
  ),
  'FOSUserBundle' => 
  array (
    'group.edit.submit' => 'Update group',
    'group.show.name' => 'Group name',
    'group.new.submit' => 'Create group',
    'group.flash.updated' => 'The group has been updated.',
    'group.flash.created' => 'The group has been created.',
    'group.flash.deleted' => 'The group has been deleted.',
    'security.login.username' => 'Username',
    'security.login.password' => 'Password',
    'security.login.remember_me' => 'Remember me',
    'security.login.submit' => 'Log in',
    'profile.show.username' => 'Username',
    'profile.show.email' => 'Email',
    'profile.edit.submit' => 'Update',
    'profile.flash.updated' => 'The profile has been updated.',
    'change_password.submit' => 'Change password',
    'change_password.flash.success' => 'The password has been changed.',
    'registration.check_email' => 'An email has been sent to %email%. It contains an activation link you must click to activate your account.
',
    'registration.confirmed' => 'Congrats %username%, your account is now activated.',
    'registration.back' => 'Back to the originating page.',
    'registration.submit' => 'Register',
    'registration.flash.user_created' => 'The user has been created successfully.',
    'registration.email.subject' => 'Welcome %username%!',
    'registration.email.message' => 'Hello %username%!

To finish activating your account - please visit %confirmationUrl%

This link can only be used once to validate your account.

Regards,
the Team.
',
    'resetting.check_email' => 'An email has been sent to you. It contains a link you must click to reset your password.
Note: You can only request a new password once within %tokenLifetime% hours.

If you don\'t get an email check your spam folder or try again.
',
    'resetting.request.username' => 'Username or email address',
    'resetting.request.submit' => 'Reset password',
    'resetting.reset.submit' => 'Change password',
    'resetting.flash.success' => 'The password has been reset successfully.',
    'resetting.email.subject' => 'Reset Password',
    'resetting.email.message' => 'Hello %username%!

You recenty requested to reset your password for your HRIS Account.
Click the link below to reset it.

%confirmationUrl%

If you did not request a password reset, please ignore this email or
reply to let us know. This password reset is only valid for the next 2 hours.


Thanks,
the Dev Team
',
    'layout.logout' => 'Log out',
    'layout.login' => 'Log in',
    'layout.register' => 'Register',
    'layout.logged_in_as' => 'Logged in as %username%',
    'form.group_name' => 'Group name',
    'form.username' => 'Username',
    'form.email' => 'Email',
    'form.current_password' => 'Current password',
    'form.password' => 'Password',
    'form.password_confirmation' => 'Repeat password',
    'form.new_password' => 'New password',
    'form.new_password_confirmation' => 'Repeat new password',
  ),
  'KnpPaginatorBundle' => 
  array (
    'label_previous' => 'Previous',
    'label_next' => 'Next',
  ),
));
$catalogue->addFallbackCatalogue($catalogueEn);

return $catalogue;
