<?php

class ISM_IdeaSciAdmin
{
  public function __construct()
  {
    // Register hooks for admin menu and settings
    add_action('admin_menu', array($this, 'add_admin_menu'));
    add_action('admin_init', array($this, 'register_settings'));

    // Enqueue your CSS file
    add_action('admin_enqueue_scripts', array($this, 'enqueue_plugin_styles'));
  }

  public function enqueue_plugin_styles()
  {
    // Enqueue your CSS file
    wp_enqueue_style('plugin-style', plugin_dir_url(__FILE__) . 'styles.css');
  }

  public function add_admin_menu()
  {
    // Add main menu and submenus
    add_menu_page(
      esc_html__('Idea-sci', 'ism-ideasci-modules'),
      esc_html__('Idea-sci', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-main-menu',
      array($this, 'render_main_page') // Callback to render the main page
    );

    add_submenu_page(
      'ideasci-main-menu',
      esc_html__('About', 'ism-ideasci-modules'),
      esc_html__('About', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-about',
      array($this, 'render_about_page') // Callback to render the about page
    );

    add_submenu_page(
      'ideasci-main-menu',
      esc_html__('Extensions', 'ism-ideasci-modules'),
      esc_html__('Extensions', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-extensions',
      array($this, 'render_extensions_page') // Callback to render the extensions page
    );
  }

  public function register_settings()
  {
    // Register plugin settings
    register_setting('ism_publications_settings', 'ism_publication_title');
    register_setting('ism_publications_settings', 'ism_publication_type');
    register_setting('ism_events_settings', 'enable_events_extension');
    register_setting('ism_events_settings', 'event_name');
    register_setting('ism_events_settings', 'participants_count');
  }

  public function render_main_page()
  {
    // Render the main page content
    require_once 'main-page.php';
  }

  public function render_about_page()
  {
    // Render the about page content
    require_once 'about-page.php';
  }

  public function render_extensions_page()
  {
    // Render the extensions page content
    require_once 'extensions/extensions-page.php';
  }
}

// Initialize the ISM_IdeaSciAdmin class
new ISM_IdeaSciAdmin();
