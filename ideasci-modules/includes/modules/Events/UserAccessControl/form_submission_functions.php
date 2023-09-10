<?php

// Constants for rate limit and duration
define('RATE_LIMIT_DURATION', 60);
define('MAX_SUBMISSIONS', 3);

/**
 * Check if the submission is within the rate limit.
 *
 * @param string $form_name Unique form name identifier.
 * @return bool True if the submission is valid; otherwise, false.
 */
function is_valid_submission($form_name)
{
  // Get the user's IP address
  $user_ip = $_SERVER['REMOTE_ADDR'];

  // Generate a unique identifier for this form submission (e.g., a combination of IP and form name)
  $form_identifier = md5($user_ip . $form_name);

  $submission_count = get_transient($form_identifier);

  if ($submission_count === FALSE) {
    // First submission within the time limit
    $submission_count = 1;
    set_transient($form_identifier, $submission_count, RATE_LIMIT_DURATION);
    return TRUE;
  } elseif ($submission_count < MAX_SUBMISSIONS) {
    // Within the rate limit, increment the submission count
    $submission_count++;
    set_transient($form_identifier, $submission_count, RATE_LIMIT_DURATION);
    return TRUE;
  } else {
    // Rate limit exceeded
    return FALSE;
  }
}
