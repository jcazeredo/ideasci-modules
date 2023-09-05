<h2><?php esc_html_e('Events Settings', 'ism-ideasci-modules'); ?></h2>

<!-- Enable/Disable Events Extension -->
<form method="post" action="options.php">
  <?php
  // Output the tab argument as a hidden input field
  if (isset($_GET['tab'])) {
    echo '<input type="hidden" name="tab" value="' . esc_attr($_GET['tab']) . '">';
  }
  settings_fields('ism_events_settings');
  do_settings_sections('ism_events_settings');
  ?>

  <div class="ism-settings-field">
    <label class="toggle-switch">
      <input type="checkbox" id="enable_events_extension" name="enable_events_extension" value="1" <?php checked('1', get_option('enable_events_extension'), true); ?> />
      <span class="slider round"></span>
    </label>
    <?php esc_html_e('Enable Events Extension', 'ism-ideasci-modules'); ?>
  </div>

  <!-- Conditional Options when Events Extension is Enabled -->
  <div id="conditional-options" style="<?php echo (get_option('enable_events_extension') === '1') ? 'display:block;' : 'display:none;'; ?>">
    <div class="ism-settings-field">
      <label for="event_name"><?php esc_html_e('Event Name', 'ism-ideasci-modules'); ?></label>
      <input type="text" id="event_name" name="event_name" value="<?php echo esc_attr(get_option('event_name')); ?>" />
    </div>

    <div class="ism-settings-field">
      <label for="participants_count"><?php esc_html_e('Number of Participants', 'ism-ideasci-modules'); ?></label>
      <input type="number" id="participants_count" name="participants_count" value="<?php echo esc_attr(get_option('participants_count')); ?>" />
    </div>
  </div>

  <div class="ism-settings-field">
    <input type="submit" class="button-primary" value="<?php esc_html_e('Save Settings', 'ism-ideasci-modules'); ?>" />
  </div>
</form>

<script>
  // JavaScript to toggle conditional options
  const enableEventsCheckbox = document.getElementById('enable_events_extension');
  const conditionalOptions = document.getElementById('conditional-options');

  enableEventsCheckbox.addEventListener('change', function() {
    if (enableEventsCheckbox.checked) {
      conditionalOptions.style.display = 'block';
    } else {
      conditionalOptions.style.display = 'none';
    }
  });
</script>