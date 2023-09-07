<?php
require_once dirname(__DIR__) . '/form_submission_functions.php';

// Initialize error and success messages
$error_message = '';
$success_message = '';

if (isset($_POST['reset_password_submit'])) {
  $is_valid_submission = is_valid_submission('reset_password_form');
  $is_valid_nonce = isset($_POST['reset_password_nonce_field']) && wp_verify_nonce($_POST['reset_password_nonce_field'], 'reset_password_nonce');

  if ($is_valid_submission && $is_valid_nonce) {
    // Get the reset key and sanitize it
    $reset_key = sanitize_text_field($_POST['reset_key']);
    $user_login = sanitize_text_field($_POST['user']);
    $user_id = check_password_reset_key($reset_key, $user_login);

    if (!is_wp_error($user_id)) {
      // Key is valid, process the password reset
      $new_password = sanitize_text_field($_POST['new_password']);
      $confirm_password = sanitize_text_field($_POST['confirm_password']);

      if ($new_password === $confirm_password) {
        // Reset the user's password
        reset_password($user_id, $new_password);

        // Send an email to the user with the password reset notification
        $user = get_userdata($user_id);
        if ($user) {
          $subject = __('Password Reset Notification', 'ism-ideasci-modules');
          $message = __('Your password has been successfully reset. If you did not initiate this action, please contact our support team.', 'ism-ideasci-modules');
          $headers = 'Content-Type: text/html; charset=UTF-8';

          wp_mail($user->user_email, $subject, $message, $headers);
        }

        // Set success message
        $success_message = '<p class="success-message">' . esc_html__('Your password has been successfully reset.', 'ism-ideasci-modules') . '</p>';
      } else {
        // Set error message if passwords do not match
        $error_message = '<p class="error-message">' . esc_html__('Passwords do not match.', 'ism-ideasci-modules') . '</p>';
      }
    } else {
      // Set error message for invalid or expired reset key
      $error_message = '<p class="error-message">' . esc_html__('Invalid or expired link.', 'ism-ideasci-modules') . '</p>';
    }
  } else {
    // Error message if submission not valid
    $error_message = '<p class="error-message">' . esc_html__('Rate limit exceeded or invalid nonce. Please try again later.', 'ism-ideasci-modules') . '</p>';
  }
}
?>

<!-- Reset Password Form -->
<div class="ism-events-form-container">
  <h2><?php esc_html_e('Reset Password Form', 'ism-ideasci-modules'); ?></h2>
  <form class="ism-events-reset-password-form" method="post">
    <?php wp_nonce_field('reset_password_nonce', 'reset_password_nonce_field'); ?>
    <div class="form-group">
      <label for="new_password"><?php esc_html_e('New Password', 'ism-ideasci-modules'); ?></label>
      <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password"><?php esc_html_e('Confirm Password', 'ism-ideasci-modules'); ?></label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div class="form-group">
      <input type="hidden" name="reset_key" value="<?php echo isset($_GET['key']) ? esc_attr($_GET['key']) : ''; ?>">
      <input type="hidden" name="user" value="<?php echo isset($_GET['user']) ? esc_attr($_GET['user']) : ''; ?>">

      <!-- Add more form fields as needed -->
      <input type="submit" name="reset_password_submit" value="<?php esc_html_e('Reset Password', 'ism-ideasci-modules'); ?>">
    </div>
  </form>

  <!-- Display error or success messages -->
  <?php echo $error_message; ?>
  <?php echo $success_message; ?>
</div>