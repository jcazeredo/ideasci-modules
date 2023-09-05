<div class="wrap">
  <h2>Custom Update Check</h2>
  <p>Click the button below to trigger the update check.</p>
  <form method="post">
    <input type="submit" name="custom_update_check" class="button button-primary" value="Check for Updates">
  </form>

  <?php
  // Handle the update check when the button is clicked
  if (isset($_POST['custom_update_check'])) {
    // Simulate the update check process
    set_site_transient('update_plugins', get_site_transient('update_plugins'), MINUTE_IN_SECONDS);

    // Output a message
    echo '<div class="updated"><p>Update check completed. Check for updates has been triggered.</p></div>';
  }
  ?>
</div>