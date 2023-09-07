<?php

/**
 * Send a welcome email to the newly registered user.
 *
 * @param int $user_id The ID of the newly registered user.
 */
function send_welcome_email_to_user($user_id)
{
  echo "emaill";

  // Get user data
  $user = get_userdata($user_id);

  // Email subject
  $subject = 'Welcome to Our Website';

  // Email message
  $message = sprintf(
    'Hello %s,

      Welcome to our website! Thank you for registering as a %s.

      We hope you enjoy your time on our website.

      Best regards,
      Your Website Team',
    $user->display_name,
    $user->roles[0] // Get the user's role
  );

  // Email headers
  $headers = array('Content-Type: text/html; charset=UTF-8');

  // Send the email
  wp_mail($user->user_email, $subject, $message, $headers);
}
