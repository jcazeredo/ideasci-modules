<?php

function ism_add_admin_menu()
{
  add_menu_page(
    esc_html__('Idea-sci', 'ism-ideasci-modules'),
    esc_html__('Idea-sci', 'ism-ideasci-modules'),
    'manage_options',
    'ideasci-main-menu',
    'ism_main_page'
  );

  add_submenu_page(
    'ideasci-main-menu',
    esc_html__('About', 'ism-ideasci-modules'),
    esc_html__('About', 'ism-ideasci-modules'),
    'manage_options',
    'ideasci-about',
    'ism_about_page'
  );
}
add_action('admin_menu', 'ism_add_admin_menu');

function ism_main_page()
{
  // Output the custom update check form
  echo '<h2>Custom Update Check</h2>';
  echo '<p>Click the button below to trigger the update check.</p>';
  echo '<form method="post">';
  echo '<input type="submit" name="custom_update_check" class="button button-primary" value="Check for Updates">';
  echo '</form>';

  // Handle the update check when the button is clicked
  if (isset($_POST['custom_update_check'])) {
    // Simulate the update check process
    set_site_transient('update_plugins', get_site_transient('update_plugins'), MINUTE_IN_SECONDS);

    // Output a message
    echo '<div class="updated"><p>Update check completed. Check for updates has been triggered.</p></div>';
  }

  echo '</div>';
}

function ism_about_page()
{
  add_action('admin_init', function () {
    // Redirect to the main Idea-sci page
    wp_redirect(admin_url('admin.php?page=ideasci-main-menu'));
    exit();
  });
}

function ism_register_settings()
{
  register_setting('ism_publications_settings', 'ism_publication_title');
  register_setting('ism_publications_settings', 'ism_publication_type');
}
add_action('admin_init', 'ism_register_settings');

function ism_extensions_page()
{
  echo '<div class="wrap">';
  echo '<h1>' . esc_html__('Extensions Page', 'ism-ideasci-modules') . '</h1>';

  // Output tabs
  $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'publications';
  echo '<h2 class="nav-tab-wrapper">';
  echo '<a href="?page=ideasci-extensions&tab=publications" class="nav-tab ' . ($current_tab == 'publications' ? 'nav-tab-active' : '') . '">' . esc_html__('Publications', 'ism-ideasci-modules') . '</a>';
  echo '<a href="?page=ideasci-extensions&tab=events" class="nav-tab ' . ($current_tab == 'events' ? 'nav-tab-active' : '') . '">' . esc_html__('Events', 'ism-ideasci-modules') . '</a>';
  echo '</h2>';

  // Output tab content
  echo '<div class="tab-content">';
  if ($current_tab == 'publications') {
    echo '<h2>' . esc_html__('Publications Settings', 'ism-ideasci-modules') . '</h2>';

    // Dummy settings controls
    echo '<form method="post" action="options.php">';

    // Output the settings_fields and do_settings_sections here
    settings_fields('ism_publications_settings');
    do_settings_sections('ism_publications_settings');

    echo '<div class="ism-settings-field">';
    echo '<label for="publication_title">' . esc_html__('Publication Title', 'ism-ideasci-modules') . '</label>';
    echo '<input type="text" id="publication_title" name="ism_publication_title" value="' . esc_attr(get_option('ism_publication_title')) . '" />';
    echo '</div>';

    echo '<div class="ism-settings-field">';
    echo '<label for="publication_type">' . esc_html__('Publication Type', 'ism-ideasci-modules') . '</label>';
    echo '<select id="publication_type" name="ism_publication_type">';
    echo '<option value="journal" ' . selected('journal', get_option('ism_publication_type'), false) . '>' . esc_html__('Journal', 'ism-ideasci-modules') . '</option>';
    echo '<option value="conference" ' . selected('conference', get_option('ism_publication_type'), false) . '>' . esc_html__('Conference', 'ism-ideasci-modules') . '</option>';
    echo '</select>';
    echo '</div>';

    echo '<div class="ism-settings-field">';
    echo '<input type="submit" class="button-primary" value="' . esc_html__('Save Settings', 'ism-ideasci-modules') . '" />';
    echo '</div>';

    echo '</form>';
  } elseif ($current_tab == 'events') {
    echo '<h2>' . esc_html__('Events Settings', 'ism-ideasci-modules') . '</h2>';
    // Add your configuration settings for Events here
  }
  echo '</div>';

  echo '</div>';
}

function ism_add_extensions_menu()
{
  add_submenu_page(
    'ideasci-main-menu',
    esc_html__('Extensions', 'ism-ideasci-modules'),
    esc_html__('Extensions', 'ism-ideasci-modules'),
    'manage_options',
    'ideasci-extensions',
    'ism_extensions_page'
  );
}
add_action('admin_menu', 'ism_add_extensions_menu');
