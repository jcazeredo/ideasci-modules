<?php
require_once dirname(__DIR__) . '/form_submission_functions.php';

// Initialize error and success messages
$error_messages = [];
$success_messages = [];

// Initialize an array to store field settings
$allowed_fields = [];

// Define an array of predefined fields
$predefined_fields = get_option('predefined_fields', []);

// Loop through the predefined fields to generate field settings
foreach ($predefined_fields as $field_name => $field_data) {
  $display_option = get_option("display_$field_name", '1');
  $required_option = get_option("required_$field_name", '1');

  $field_settings = [
    'label' => esc_html($field_data['label']),
    'type' => esc_html($field_data['type']),
    'required' => $required_option === '1',
    'display' => $display_option === '1',
  ];

  $allowed_fields[$field_name] = $field_settings;
}

if (isset($_POST['registration_flag'])) {
  $is_valid_submission = is_valid_submission('registration_form');
  $is_valid_nonce = isset($_POST['registration_nonce_field']) && wp_verify_nonce($_POST['registration_nonce_field'], 'registration_nonce');

  // Check if all required fields are provided
  $required_fields = ['email', 'confirm_email', 'password', 'confirm_password', 'terms_conditions'];
  $missing_required_fields = [];

  foreach ($allowed_fields as $field_name => $field_settings) {
    if ($field_settings['required']) {
      $required_fields[] = $field_name;
    }
  }

  foreach ($required_fields as $required_field) {
    if (empty($_POST[$required_field])) {
      $missing_required_fields[] = $required_field;
      $error_messages[] = sprintf(esc_html__('%s is required.', 'ism-ideasci-modules'), $field_settings['label']);
    }
  }

  // Validate email format
  $email = sanitize_email($_POST['email']);
  $confirm_email = sanitize_email($_POST['confirm_email']);

  if ($email !== $confirm_email) {
    $error_messages[] = esc_html__('Email and Confirm Email must match.', 'ism-ideasci-modules');
  }

  // Password validation (add more rules as needed)
  $password = sanitize_text_field($_POST['password']);
  $confirm_password = sanitize_text_field($_POST['confirm_password']);

  if ($password !== $confirm_password) {
    $error_messages[] = esc_html__('Password and Confirm Password must match.', 'ism-ideasci-modules');
  } elseif (strlen($password) < 8) {
    $error_messages[] = esc_html__('Password must be at least 8 characters long.', 'ism-ideasci-modules');
  }

  // Additional input validation for other fields here
  if (empty($missing_required_fields) && empty($error_messages) && $is_valid_submission && $is_valid_nonce) {
    // Registration Logic: Create User and Insert Metafield Data
    $user_data = [
      'user_login' => $email,
      'user_email' => $email,
      'user_pass' => $password,
    ];

    $user_id = wp_insert_user($user_data);

    if (!is_wp_error($user_id)) {
      // Registration success, now insert metafield data
      foreach ($allowed_fields as $field_name => $field_settings) {
        if ($field_settings['display']) {
          $field_value = sanitize_text_field($_POST[$field_name]);
          update_user_meta($user_id, $field_name, $field_value);
        }
      }
      $success_messages[] = esc_html__('Registration successful!', 'ism-ideasci-modules');
    } else {
      $error_messages[] = esc_html__('User registration failed. Please try again later.', 'ism-ideasci-modules');
    }
  }
}
?>

<!-- Registration Form -->
<div class="ism-events-form-container">
  <h2><?php esc_html_e('Registration Form', 'ism-ideasci-modules'); ?></h2>
  <form id="registration_form" class="ism-events-registration-form" method="post">
    <?php wp_nonce_field('registration_nonce', 'registration_nonce_field'); ?>
    <input type="hidden" name="registration_flag" value="1"> <!-- Hidden input as a flag -->
    <div class="form-group">
      <label for="email" class="ism-events-form-required-field"><?php echo esc_html__('Email', 'ism-ideasci-modules') ?></label>
      <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="confirm_email" class="ism-events-form-required-field"><?php echo esc_html__('Confirm Email', 'ism-ideasci-modules') ?></label>
      <input type="email" id="confirm_email" name="confirm_email" required>
    </div>
    <div class="form-group">
      <label for="password" class="ism-events-form-required-field"><?php echo esc_html__('Password', 'ism-ideasci-modules') ?></label>
      <input type="password" id="password" name="password" required>
    </div>
    <div class="form-group">
      <label for="confirm_password" class="ism-events-form-required-field"><?php echo esc_html__('Confirm Password', 'ism-ideasci-modules') ?></label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <?php
    // Loop through the allowed fields and generate form elements
    foreach ($allowed_fields as $field_name => $field_settings) {
      if ($field_settings['display']) {
        // Check if the field is required and add the class accordingly
        $required_class = $field_settings['required'] ? ' ism-events-form-required-field' : '';
    ?>
        <div class="form-group">
          <label for="<?php echo $field_name; ?>" class="<?php echo $required_class; ?>"><?php echo $field_settings['label']; ?></label>
          <input type="<?php echo $field_settings['type']; ?>" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" <?php if ($field_settings['required']) echo 'required'; ?>>
        </div>
    <?php
      }
    }
    ?>

    <!-- Add more form fields as needed -->
    <div class="form-group">
      <label for="terms_conditions">
        <input type="checkbox" id="terms_conditions" name="terms_conditions">
        <?php esc_html_e('I agree to the terms and conditions', 'ism-ideasci-modules'); ?>
      </label>
    </div>

    <input type="submit" name="registration_submit" id="registration_submit" value="<?php esc_html_e('Register', 'ism-ideasci-modules'); ?>">
  </form>

  <div class="ism-events-form-message">
    <!-- Display error or success messages -->
    <?php foreach ($error_messages as $error_message) : ?>
      <p class="error-message"><?php echo $error_message; ?></p>
    <?php endforeach; ?>

    <?php foreach ($success_messages as $success_message) : ?>
      <p class="success-message"><?php echo $success_message; ?></p>
    <?php endforeach; ?>
  </div>
</div>

<script>
  jQuery(document).ready(function($) {
    jQuery("#registration_submit").click(function(e) {
      e.preventDefault();

      // Clear previous error messages
      jQuery(".error-message").remove();

      var error_messages = [];

      // Check if all required fields are provided
      var required_fields = [];
      jQuery(":input[required]").each(function() {
        var field_name = jQuery(this).attr("name");
        var field_label = jQuery(
          'label[for="' + field_name + '"]'
        ).text();
        required_fields.push({
          name: field_name,
          label: field_label,
        });
      });

      jQuery.each(required_fields, function(index, field) {
        var field_value = jQuery("#" + field.name).val(); // Use field.name to access the field's name
        var field_label = field.label;

        if (field_value === "") {
          error_messages.push(field_label + " is required.");
        }
      });

      // Validate email format and match
      var email = jQuery("#email").val();
      var confirm_email = jQuery("#confirm_email").val();

      if (email !== confirm_email) {
        error_messages.push("Email and Confirm Email must match.");
      }

      // Validate password format and match (add more rules as needed)
      var password = jQuery("#password").val();
      var confirm_password = jQuery("#confirm_password").val();

      if (password !== confirm_password) {
        error_messages.push(
          "Password and Confirm Password must match."
        );
      } else if (password.length < 8) {
        error_messages.push(
          "Password must be at least 8 characters long."
        );
      }

      // Validate terms and conditions agreement
      var terms_conditions = jQuery("#terms_conditions").prop(
        "checked"
      ); // Assuming this is a checkbox input

      if (!terms_conditions) {
        error_messages.push(
          "Please agree to the terms and conditions."
        );
      }

      // Display error messages
      if (error_messages.length > 0) {
        var error_message_html =
          '<p class="error-message">' +
          error_messages.join("<br>") +
          "</p>";
        jQuery(".ism-events-form-message").append(error_message_html);
      } else {
        // If no errors, set the hidden input flag and submit the form
        jQuery("#registration_flag").val("1");
        jQuery("#registration_form")[0].submit();
      }
    });
  });
</script>