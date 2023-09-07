<?php

class ISM_EventsAdmin
{

  private $predefined_fields = [];

  public function __construct()
  {
    $this->predefined_fields = [
      'username' => [
        'label' => esc_html__('Username', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'email' => [
        'label' => esc_html__('Email', 'ism-ideasci-modules'),
        'type' => 'email',
      ],
      'confirm_email' => [
        'label' => esc_html__('Confirm Email', 'ism-ideasci-modules'),
        'type' => 'email',
      ],
      'password' => [
        'label' => esc_html__('Password', 'ism-ideasci-modules'),
        'type' => 'password',
      ],
      'first_name' => [
        'label' => esc_html__('First Name', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'last_name' => [
        'label' => esc_html__('Last Name', 'ism-ideasci-modules'),
        'type' => '<input type="text" name="last_name">',
      ],
      'affiliation' => [
        'label' => esc_html__('Affiliation', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'city' => [
        'label' => esc_html__('City', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'country' => [
        'label' => esc_html__('Country', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'contact_number' => [
        'label' => esc_html__('Contact Number', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
      'how_heard' => [
        'label' => esc_html__('How Heard', 'ism-ideasci-modules'),
        'type' => 'text',
      ],
    ];
  }

  public function register_menu()
  {
    add_submenu_page(
      'ideasci-main-menu',
      esc_html__('Events Module', 'ism-ideasci-modules'),
      esc_html__('Events Module', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-events',
      [$this, 'render'] // Callback to render the extensions page
    );
  }

  public function register_settings()
  {
    register_setting('ism_events_settings', 'enable_publications_extension');
    register_setting('ism_events_settings', 'recaptcha_enabled');
    register_setting('ism_events_settings', 'recaptcha_site_key');
    register_setting('ism_events_settings', 'recaptcha_secret_key');

    update_option('predefined_fields', $this->predefined_fields);

    $this->register_field_settings();
  }

  private function register_field_settings()
  {
    foreach ($this->predefined_fields as $field_name => $field_label) {
      register_setting('ism_events_settings', "display_$field_name");
      register_setting('ism_events_settings', "required_$field_name");
    }
  }

  public function render()
  {
    require_once plugin_dir_path(__FILE__) . 'events-page.php';
  }
}
