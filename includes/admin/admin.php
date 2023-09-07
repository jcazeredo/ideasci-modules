<?php

class ISM_IdeaSciAdmin
{
  private $modules = [];
  private $modules_folder = "extensions";

  public function __construct()
  {
    $moduleDefinitions = [
      'Events' => 'ISM_EventsAdmin',
      // Add more modules as needed
    ];

    add_action('admin_enqueue_scripts', [$this, 'enqueue_plugin_assets']);

    $this->load_modules($moduleDefinitions);

    add_action('admin_menu', [$this, 'add_admin_menu']);
    add_action('admin_init', [$this, 'register_settings']);
  }

  private function load_modules($moduleDefinitions)
  {
    foreach ($moduleDefinitions as $folder => $class) {
      $module_file_path = plugin_dir_path(__FILE__) . "{$this->modules_folder}/{$folder}/{$folder}.php";

      require_once $module_file_path;

      $this->modules[] = new $class();
    }
  }

  public function enqueue_plugin_assets()
  {
    wp_enqueue_style('plugin-style', plugin_dir_url(__FILE__) . 'styles.css');
    wp_enqueue_script('plugin-script', plugin_dir_url(__FILE__) . 'script.js', ['jquery'], null, true);
  }

  public function add_admin_menu()
  {
    add_menu_page(
      esc_html__('Idea-sci', 'ism-ideasci-modules'),
      esc_html__('Idea-sci', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-main-menu',
      [$this, 'render_main_page']
    );

    add_submenu_page(
      'ideasci-main-menu',
      esc_html__('About', 'ism-ideasci-modules'),
      esc_html__('About', 'ism-ideasci-modules'),
      'manage_options',
      'ideasci-about',
      [$this, 'render_about_page']
    );

    $this->register_menus();
  }

  private function register_menus()
  {
    foreach ($this->modules as $module) {
      if (method_exists($module, 'register_menu')) {
        $module->register_menu();
      }
    }
  }

  public function register_settings()
  {
    $this->register_module_settings();
  }

  private function register_module_settings()
  {
    foreach ($this->modules as $module) {
      if (method_exists($module, 'register_settings')) {
        $module->register_settings();
      }
    }
  }

  public function render_main_page()
  {
    require_once plugin_dir_path(__FILE__) . 'templates/main-page.php';
  }

  public function render_about_page()
  {
    require_once plugin_dir_path(__FILE__) . 'templates/about-page.php';
  }
}

new ISM_IdeaSciAdmin();
