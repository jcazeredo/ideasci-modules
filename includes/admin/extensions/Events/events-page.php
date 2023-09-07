<div class="wrap">
  <h2><?php esc_html_e('Event Registration Form Settings', 'ism-ideasci-modules'); ?></h2>
  <form method="post" action="options.php">
    <?php settings_fields('ism_events_settings'); ?>
    <?php do_settings_sections('ism_events_settings'); ?>

    <table class="form-table">
      <?php
      // Retrieve the predefined fields and their labels from the option
      $predefined_fields = get_option('predefined_fields', array());

      // Loop through the predefined fields
      foreach ($predefined_fields as $field_name => $field_data) {
        // Get field settings from options
        $display = get_option("display_$field_name", '1'); // Default: Displayed
        $required = get_option("required_$field_name", '1'); // Default: Required
      ?>
        <tr valign="top">
          <th scope="row"><?php echo esc_html($field_data['label']); ?></th>
          <td>
            <label for="display_<?php echo $field_name; ?>">
              <input type="checkbox" id="display_<?php echo $field_name; ?>" name="display_<?php echo $field_name; ?>" value="1" <?php checked('1', $display); ?>>
              <?php esc_html_e('Display', 'ism-ideasci-modules'); ?>
            </label>
            <label for="required_<?php echo $field_name; ?>">
              <input type="checkbox" id="required_<?php echo $field_name; ?>" name="required_<?php echo $field_name; ?>" value="1" <?php checked('1', $required); ?> <?php disabled(!$display); ?>>
              <?php esc_html_e('Required', 'ism-ideasci-modules'); ?>
            </label>
          </td>
        </tr>
      <?php } ?>
    </table>

    <?php submit_button(); ?>
  </form>

  <script>
    jQuery(document).ready(function($) {
      setupDisplayRequiredCheckboxes();
    });
  </script>
</div>