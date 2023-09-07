<?php
require_once dirname(__DIR__) . '/form_submission_functions.php';

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Initialize an array to store field settings
$allowed_fields = [];

// Define an array of predefined fields
$predefined_fields = get_option('predefined_fields', []);

// Loop through the predefined fields to generate field settings
foreach ($predefined_fields as $field_name => $field_data) {
  $display_option = get_option("display_$field_name", '1');
  $required_option = get_option("required_$field_name", '1');

  $field_settings = array(
    'label' => esc_html($field_data["label"]),
    'type' => esc_html($field_data["type"]),
    'required' => $required_option === '1',
    'display' => $display_option === '1',
  );

  $allowed_fields[$field_name] = $field_settings;
}

if (isset($_POST['registration_submit'])) {
  $is_valid_submission = is_valid_submission('registration_form');
  $is_valid_nonce = isset($_POST['registration_nonce_field']) && wp_verify_nonce($_POST['registration_nonce_field'], 'registration_nonce');

  if ($is_valid_submission && $is_valid_nonce) {
    // Initialize an array to store field errors
    $field_errors = [];

    // Initialize an array to store sanitized values
    $sanitized_values = [];

    // Loop through the allowed fields
    foreach ($allowed_fields as $field_name => $field_settings) {
      if ($field_settings['display']) {
        $user_input = isset($_POST[$field_name]) ? $_POST[$field_name] : '';

        // Validate and sanitize the user input
        if ($field_settings['required']) {
          if (empty($user_input)) {
            $field_errors[$field_name] = esc_html__('This field is required.', 'ism-ideasci-modules');
          }
        }

        // Additional validation and sanitation if needed

        $sanitized_values[$field_name] = sanitize_text_field($user_input);
      }
    }

    // Check for overall form validation
    if (empty($field_errors)) {
      // All fields are valid, proceed with user registration
      // ...
    } else {
      // Display field-specific error messages
      foreach ($allowed_fields as $field_name => $field_settings) {
        if ($field_settings['display'] && !empty($field_errors[$field_name])) {
          echo '<p class="error-message">' . esc_html($field_errors[$field_name]) . '</p>';
        }
      }
    }
  }
}
?>

<!-- Registration Form -->
<div class="ism-events-form-container">
  <h2><?php esc_html_e('Registration Form', 'ism-ideasci-modules'); ?></h2>
  <form id="registration_form" class="ism-events-registration-form" method="post">
    <?php wp_nonce_field('registration_nonce', 'registration_nonce_field'); ?>

    <?php
    // Loop through the allowed fields and generate form elements
    foreach ($allowed_fields as $field_name => $field_settings) {
      if ($field_settings['display']) {
    ?>
        <div class="form-group">
          <label for="<?php echo $field_name; ?>"><?php echo $field_settings['label']; ?></label>
          <input type=<?php echo $field_settings['type']; ?> id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" <?php if ($field_settings['required']) echo 'required'; ?>>
        </div>
    <?php
      }
    }
    ?>

    <!-- Add more form fields as needed -->
    <div class="form-group">
      <label for="terms_conditions">
        <input type="checkbox" id="terms_conditions" name="terms_conditions" required>
        <?php esc_html_e('I agree to the terms and conditions', 'ism-ideasci-modules'); ?>
      </label>
    </div>

    <input type="submit" name="registration_submit" id="registration_submit" value="<?php esc_html_e('Register', 'ism-ideasci-modules'); ?>">
  </form>

  <div class="ism-events-form-message">
    <!-- Display error or success messages -->
    <?php if (!empty($error_message)) : ?>
      <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)) : ?>
      <p class="success-message"><?php echo $success_message; ?></p>
    <?php endif; ?>
  </div>
</div>

<script>
  // Call the function to do the form validation
  registrationFormValidation();
</script>