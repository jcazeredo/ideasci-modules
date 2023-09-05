<div class="wrap">
  <h1><?php esc_html_e('Extensions Page', 'ism-ideasci-modules'); ?></h1>

  <!-- Output tabs -->
  <?php
  $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'publications';
  echo '<h2 class="nav-tab-wrapper">';
  echo '<a href="?page=ideasci-extensions&tab=publications" class="nav-tab ' . ($current_tab == 'publications' ? 'nav-tab-active' : '') . '">' . esc_html__('Publications', 'ism-ideasci-modules') . '</a>';
  echo '<a href="?page=ideasci-extensions&tab=events" class="nav-tab ' . ($current_tab == 'events' ? 'nav-tab-active' : '') . '">' . esc_html__('Events', 'ism-ideasci-modules') . '</a>';
  echo '</h2>';
  ?>

  <!-- Output tab content -->
  <div class="tab-content">
    <?php if ($current_tab == 'publications') : ?>
      <h2><?php esc_html_e('Publications Settings', 'ism-ideasci-modules'); ?></h2>

      <!-- Dummy settings controls -->
      <form method="post" action="options.php">

        <?php
        // Output the settings_fields and do_settings_sections here
        settings_fields('ism_publications_settings');
        do_settings_sections('ism_publications_settings');
        ?>

        <div class="ism-settings-field">
          <label for="publication_title"><?php esc_html_e('Publication Title', 'ism-ideasci-modules'); ?></label>
          <input type="text" id="publication_title" name="ism_publication_title" value="<?php echo esc_attr(get_option('ism_publication_title')); ?>" />
        </div>

        <div class="ism-settings-field">
          <label for="publication_type"><?php esc_html_e('Publication Type', 'ism-ideasci-modules'); ?></label>
          <select id="publication_type" name="ism_publication_type">
            <option value="journal" <?php selected('journal', get_option('ism_publication_type'), true); ?>><?php esc_html_e('Journal', 'ism-ideasci-modules'); ?></option>
            <option value="conference" <?php selected('conference', get_option('ism_publication_type'), true); ?>><?php esc_html_e('Conference', 'ism-ideasci-modules'); ?></option>
          </select>
        </div>

        <div class="ism-settings-field">
          <input type="submit" class="button-primary" value="<?php esc_html_e('Save Settings', 'ism-ideasci-modules'); ?>" />
        </div>

      </form>
    <?php elseif ($current_tab == 'events') : ?>
      <h2><?php esc_html_e('Events Settings', 'ism-ideasci-modules'); ?></h2>
      <!-- Add your configuration settings for Events here -->
    <?php endif; ?>
  </div>
</div>