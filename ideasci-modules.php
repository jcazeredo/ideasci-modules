<?php
/*
Plugin Name: Idea-sci Modules
Plugin URI:  https://www.idea-sci.com/
Description: Custom modules for idea-sci websites
Version:     1.0.0
Author:      Idea-sci
Author URI:  https://www.idea-sci.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ism-ideasci-modules
Domain Path: /languages

Idea-sci Modules is a free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Idea-sci Modules is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Idea-sci Modules. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined('ABSPATH') || die('No script kiddies please!');

define('ISM_VERSION', '1.0.0');
define('ISM_OPTION', 'idea-sci-modules');
define('ISM_BASENAME', plugin_basename(__FILE__));
define('ISM_PATH', plugin_dir_url(__FILE__));

if (!function_exists('ism_initialize_extension')) :
  /**
   * Creates the extension's main class instance.
   *
   * @since 1.0.0
   */
  function ism_initialize_extension()
  {
    require_once plugin_dir_path(__FILE__) . 'includes/IdeaSciModules.php';
  }
  add_action('divi_extensions_init', 'ism_initialize_extension');
endif;

// Create a custom admin page and add a button
function custom_admin_page()
{
  add_menu_page('Custom Update Check', 'Custom Update Check', 'manage_options', 'custom-update-check', 'custom_update_check_page');
}

function custom_update_check_page()
{
?>
  <div class="wrap">
    <h2>Custom Update Check</h2>
    <p>Click the button below to trigger the update check.</p>
    <form method="post">
      <input type="submit" name="custom_update_check" class="button button-primary" value="Check for Updates">
    </form>
  </div>
<?php
}
