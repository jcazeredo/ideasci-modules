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

  function get_fields()
  {
    return array();
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
