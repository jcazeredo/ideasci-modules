<?php
require_once dirname(__DIR__) . '/form_submission_functions.php';

// Initialize error and success messages
$error_message = '';
$success_message = '';

if (isset($_POST['forget_password_submit'])) {
  $is_valid_submission = is_valid_submission('forget_password_form');
  $is_valid_nonce = isset($_POST['reset_password_nonce_field']) && wp_verify_nonce($_POST['reset_password_nonce_field'], 'reset_password_nonce');

  if ($is_valid_submission && $is_valid_nonce) {

    $user_email = sanitize_email($_POST['email']);

    // Sanitize and validate the user's email
    $user = get_user_by('email', $user_email);

    if ($user) {
      // Generate a password reset key
      $reset_key = get_password_reset_key($user);

      if (!is_wp_error($reset_key)) {
        // Send an email to the user with the password reset link
        $reset_link = esc_url(add_query_arg(
          array('form' => 'reset-password', 'user' => $user->user_login, 'key' => $reset_key),
          home_url($_SERVER['REQUEST_URI'])
        ));

        // Sanitize the reset link using esc_url_raw
        $reset_link = esc_url_raw($reset_link);

        // Get the translated subject and message
        $subject = __('Password Reset Request', 'ism-ideasci-modules');
        $message = sprintf(
          __('Please click the following link to reset your password: <a href="%s">%s</a>', 'ism-ideasci-modules'),
          $reset_link,
          $reset_link
        );

        $headers = 'Content-Type: text/html; charset=UTF-8';

        wp_mail($user_email, $subject, $message, $headers);

        // Success message
        $success_message = '<p class="success-message">' . esc_html__('If an account exists with the provided email, you will receive an email with instructions to reset your password.', 'ism-ideasci-modules') . '</p>';
      } else {
        // Error message if key generation fails
        $error_message = '<p class="error-message">' . esc_html__('Submission error. Please try again later.', 'ism-ideasci-modules') . '</p>';
      }
    }
  } else {
    // Error message if submission not valid
    $error_message = '<p class="error-message">' . esc_html__('Rate limit exceeded. Please try again later.', 'ism-ideasci-modules') . '</p>';
  }
}
?>

<!-- Forget Password Form -->
<div class="ism-events-form-container">
  <h2><?php esc_html_e('Forget Password Form', 'ism-ideasci-modules'); ?></h2>
  <form method="post">
    <?php wp_nonce_field('reset_password_nonce', 'reset_password_nonce_field'); ?>
    <div class="form-group">
      <label for="email"><?php esc_html_e('Email', 'ism-ideasci-modules'); ?></label>
      <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
      <input type="submit" name="forget_password_submit" value="<?php esc_html_e('Reset Password', 'ism-ideasci-modules'); ?>">
    </div>
  </form>

  <!-- Display error or success messages -->
  <?php if (!empty($error_message)) : ?>
    <p class="error-message"><?php echo $error_message; ?></p>
  <?php endif; ?>

  <?php if (!empty($success_message)) : ?>
    <p class="success-message"><?php echo $success_message; ?></p>
  <?php endif; ?>
</div>