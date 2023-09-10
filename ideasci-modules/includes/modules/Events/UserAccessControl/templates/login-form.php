<div class="ism-events-form-container">
  <h2><?php esc_html_e('Login Form', 'ism-ideasci-modules'); ?></h2>
  <form class="ism-events-login-form" method="post">
    <div class="form-group">
      <label for="username"><?php esc_html_e('Username', 'ism-ideasci-modules'); ?></label>
      <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
      <label for="password"><?php esc_html_e('Password', 'ism-ideasci-modules'); ?></label>
      <input type="password" id="password" name="password" required>
    </div>
    <!-- Add more form fields as needed -->
    <input type="submit" name="login_submit" value="<?php esc_html_e('Log In', 'ism-ideasci-modules'); ?>">
  </form>
</div>