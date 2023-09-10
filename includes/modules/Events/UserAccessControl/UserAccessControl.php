<?php

class ISM_Events_User_Access_Control extends ET_Builder_Module
{
  public $slug = 'ism_events_uac';

  public $vb_support = 'on';

  function init()
  {
    $this->name = esc_html__('Events - User Access Control', 'ism-ideasci-modules');

    $this->icon_path = plugin_dir_path(__FILE__) . 'icon.svg';

    $this->settings_modal_toggles = array(
      'general' => array(
        'toggles' => array(
          'registration_form' => esc_html__('Registration Form', 'ism-ideasci-modules'),
          'forget_password_form' => esc_html__('Forget Password Form', 'ism-ideasci-modules'),
          'reset_password_form' => esc_html__('Reset Password Form', 'ism-ideasci-modules'),
          'login_form' => esc_html__('Login Form', 'ism-ideasci-modules'),
        ),
      ),
    );
  }

  public function get_settings_modal_toggles()
  {
    return array(
      'general'  => array(
        'toggles' => array(
          'form_title' => array(
            'title' => esc_html__('Form Title', 'ism-ideasci-modules'),
          ),
          'display_fields' => array(
            'title' => esc_html__('Display Fields', 'ism-ideasci-modules'),
          ),
          'require_fields' => array(
            'title' => esc_html__('Require Fields', 'ism-ideasci-modules'),
          ),
        ),
      ),
      'advanced' => array(
        'toggles' => array(
          'form_title' => array(
            'title' => esc_html__('Form Title', 'ism-ideasci-modules'),
          ),
          'form_fields' => array(
            'title' => esc_html__('Fields', 'ism-ideasci-modules'),
          ),
          'form_labels' => array(
            'title' => esc_html__('Labels', 'ism-ideasci-modules'),
          ),
        ),
      ),
    );
  }

  function get_fields()
  {
    // Define all the possible registration fields
    $this->props['registration_form_fields'] = array(
      'first_name' => array(
        'label' => esc_html__('First Name', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
      'last_name' => array(
        'label' => esc_html__('Last Name', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
      'email' => array(
        'label' => esc_html__('Email', 'ism-ideasci-modules'),
        'type' => 'email',
      ),
      'affiliation' => array(
        'label' => esc_html__('Affiliation', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
      'city' => array(
        'label' => esc_html__('City', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
      'country' => array(
        'label' => esc_html__('Country', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
      'contact_number' => array(
        'label' => esc_html__('Contact Number', 'ism-ideasci-modules'),
        'type' => 'tel',
      ),
      'how_heard' => array(
        'label' => esc_html__('How Heard', 'ism-ideasci-modules'),
        'type' => 'text',
      ),
    );

    // Initialize an empty array to store the fields
    $fields_array = array();

    // Loop through the predefined fields and create the field settings
    foreach ($this->props['registration_form_fields'] as $slug => $field_data) {
      // Extract the field name and type from the field data array
      $field_name = $field_data['label'];

      // Define the Display Field
      $fields_array['display_' . $slug] = array(
        'label' => esc_html__('Show', 'ism-ideasci-modules') . ' ' . esc_html__($field_name . ' Field', 'ism-ideasci-modules'),
        'type' => 'yes_no_button',
        'option_category' => 'configuration',
        'options' => array(
          'on' => esc_html__('Yes', 'ism-ideasci-modules'),
          'off' => esc_html__('No', 'ism-ideasci-modules'),
        ),
        'default' => 'on',
        'tab_slug' => 'general',
        'toggle_slug' => 'display_fields',
        'description' => esc_html__('This will display or not the field.', 'ism-ideasci-modules'),
      );

      // Define the Required Field
      $fields_array['require_' . $slug] = array(
        'label' => esc_html__('Require', 'ism-ideasci-modules') . ' ' . esc_html__($field_name . ' Field', 'ism-ideasci-modules'),
        'type' => 'yes_no_button',
        'option_category' => 'configuration',
        'options' => array(
          'on' => esc_html__('Yes', 'ism-ideasci-modules'),
          'off' => esc_html__('No', 'ism-ideasci-modules'),
        ),
        'default' => 'on',
        'tab_slug' => 'general',
        'toggle_slug' => 'require_fields',
        'description' => esc_html__('This will require or not the field.', 'ism-ideasci-modules'),
        'show_if' => array(
          'display_' . $slug => 'on',
        ),
      );
    }

    // Define Form Title
    $fields_array['registration_form_title'] = array(
      'label' => esc_html__('Title', 'ism-ideasci-modules'),
      'type' => 'text',
      'option_category' => 'configuration',
      'default' => 'Registration Form',
      'tab_slug' => 'general',
      'toggle_slug' => 'form_title',
      'description' => esc_html__('This is the title that will appear in the form.', 'ism-ideasci-modules'),
      'dynamic_content' => 'text',
    );

    return $fields_array;
  }


  function get_advanced_fields_config()
  {
    return array();
  }

  function render($render_slug, $attrs, $content = null)
  {
    // Determine which form to display based on URL parameters
    $form_to_display = '';

    if (isset($_GET['form'])) {
      $form_type = sanitize_text_field($_GET['form']);

      // Import and display the form based on the URL parameter
      switch ($form_type) {
        case 'registration':
          ob_start();
          include(plugin_dir_path(__FILE__) . 'templates/registration-form.php');
          $form_to_display = ob_get_clean();
          break;
        case 'forget-password':
          ob_start();
          include(plugin_dir_path(__FILE__) . 'templates/forget-password-form.php');
          $form_to_display = ob_get_clean();
          break;
        case 'reset-password':
          ob_start();
          include(plugin_dir_path(__FILE__) . 'templates/reset-password-form.php');
          $form_to_display = ob_get_clean();
          break;
        case 'login':
          ob_start();
          include(plugin_dir_path(__FILE__) . 'templates/login-form.php');
          $form_to_display = ob_get_clean();
          break;
        default:
          $form_to_display = esc_html__('Invalid form type.', 'ism-ideasci-modules');
      }
    } else {
      // Display a default message if no form is specified
      $form_to_display = esc_html__('Please specify a form type in the URL parameters.', 'ism-ideasci-modules');
    }

    return $form_to_display;
  }
}

new ISM_Events_User_Access_Control;
