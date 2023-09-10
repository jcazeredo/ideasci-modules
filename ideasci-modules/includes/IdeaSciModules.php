<?php
class ISM_IdeaSciModules extends DiviExtension
{
  /**
   * The gettext domain for the extension's translations.
   *
   * @since 1.0.0
   *
   * @var string
   */
  public $gettext_domain = 'ism-ideasci-modules';

  /**
   * The extension's WP Plugin name.
   *
   * @since 1.0.0
   *
   * @var string
   */
  public $name = 'ideasci-modules';

  /**
   * The extension's version
   *
   * @since 1.0.0
   *
   * @var string
   */
  public $version = '1.0.0';

  /**
   * ISM_IdeaSciModules constructor.
   *
   * @param string $name
   * @param array  $args
   */
  public function __construct($name = 'ideasci-modules', $args = array())
  {
    $this->plugin_dir              = plugin_dir_path(__FILE__);
    $this->plugin_dir_url          = plugin_dir_url($this->plugin_dir);
    $this->_frontend_js_data   = array(
      'ajaxurl'   => admin_url('admin-ajax.php'),
      'ajaxnonce'  => wp_create_nonce('ism-nonce'),
    );
    $this->_builder_js_data        = array(
      'i10n' => array(
        'ism_cta_all_options' => array(
          'basic_fields'         => esc_html__('Basic Fields', 'ism-ideasci-modules'),
          'text'                 => esc_html__('Text', 'ism-ideasci-modules'),
          'textarea'             => esc_html__('Textarea', 'ism-ideasci-modules'),
          'select'               => esc_html__('Select', 'ism-ideasci-modules'),
          'toggle'               => esc_html__('Toggle', 'ism-ideasci-modules'),
          'multiple_buttons'     => esc_html__('Multiple Buttons', 'ism-ideasci-modules'),
          'multiple_checkboxes'  => esc_html__('Multiple Checkboxes', 'ism-ideasci-modules'),
          'input_range'          => esc_html__('Input Range', 'ism-ideasci-modules'),
          'input_datetime'       => esc_html__('Input Date Time', 'ism-ideasci-modules'),
          'input_margin'         => esc_html__('Input Margin', 'ism-ideasci-modules'),
          'checkboxes_category'  => esc_html__('Checkboxes Category', 'ism-ideasci-modules'),
          'select_sidebar'       => esc_html__('Select Sidebar', 'ism-ideasci-modules'),
          'code_fields'          => esc_html__('Code Fields', 'ism-ideasci-modules'),
          'codemirror'           => esc_html__('Codemirror', 'ism-ideasci-modules'),
          'prop_value'           => esc_html__('Prop value: ', 'ism-ideasci-modules'),
          'rendered_prop_value'  => esc_html__('Rendered prop value: ', 'ism-ideasci-modules'),
          'form_fields'          => esc_html__('Form Fields', 'ism-ideasci-modules'),
          'option_list'          => esc_html__('Option List', 'ism-ideasci-modules'),
          'option_list_checkbox' => esc_html__('Option List Checkbox', 'ism-ideasci-modules'),
          'option_list_radio'    => esc_html__('Option List Radio', 'ism-ideasci-modules'),
          'typography_fields'    => esc_html__('Typography Fields', 'ism-ideasci-modules'),
          'select_font_icon'     => esc_html__('Select Font Icon', 'ism-ideasci-modules'),
          'select_text_align'    => esc_html__('Select Text Align', 'ism-ideasci-modules'),
          'select_font'          => esc_html__('Select Font', 'ism-ideasci-modules'),
          'color_fields'         => esc_html__('Color Fields', 'ism-ideasci-modules'),
          'color'                => esc_html__('Color', 'ism-ideasci-modules'),
          'color_alpha'          => esc_html__('Color Alpha', 'ism-ideasci-modules'),
          'upload_fields'        => esc_html__('Upload Fields', 'ism-ideasci-modules'),
          'upload'               => esc_html__('Upload', 'ism-ideasci-modules'),
          'advanced_fields'      => esc_html__('Advanced Fields', 'ism-ideasci-modules'),
          'tab_1_text'           => esc_html__('Tab 1 Text', 'ism-ideasci-modules'),
          'tab_2_text'           => esc_html__('Tab 2 Text', 'ism-ideasci-modules'),
          'presets_shadow'       => esc_html__('Presets Shadow', 'ism-ideasci-modules'),
          'preset_affected_1'    => esc_html__('Preset Affected 1', 'ism-ideasci-modules'),
          'preset_affected_2'    => esc_html__('Preset Affected 2', 'ism-ideasci-modules'),
        ),
      ),
    );

    parent::__construct($name, $args);
    $this->plugin_setup();

    add_action('wp_ajax_ajax_search_callback', array($this, 'ajax_search_callback'));
    add_filter('nonce_user_logged_out', array($this, 'ism_ajax_search_update_nonce'), 10, 2);
    add_action('wp_ajax_nopriv_ajax_search_callback', array($this, 'ajax_search_callback'));
  }

  public function ism_ajax_search_update_nonce($uid, $action = -1)
  {
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && !is_user_logged_in() && $action === 'ism-nonce') {
      return get_current_user_id();
    }
    return $uid;
  }


  /**
   * plugin setup function.
   *
   *@since 1.0.0
   */
  public function plugin_setup()
  {
    if (is_admin()) {
      require_once plugin_dir_path(__FILE__) . 'core/class-update.php';
      require_once plugin_dir_path(__FILE__) . 'core/class-installation.php';
      require_once plugin_dir_path(__FILE__) . 'admin/Admin.php';
    }
  }

  function ajax_search_callback()
  {

    $search_term     = isset($_POST['search_term']) ? trim(sanitize_text_field(wp_unslash($_POST['search_term']))) : '';
    $post_types      = isset($_POST['post_types']) ? sanitize_text_field(wp_unslash($_POST['post_types'])) : '';
    $display_fields    = isset($_POST['display_fields']) ? sanitize_text_field(wp_unslash($_POST['display_fields'])) : '';
    $date_format    = isset($_POST['date_format']) ? sanitize_text_field(wp_unslash($_POST['date_format'])) : 'M, Y';
    $number_of_results   = isset($_POST['number_of_results']) ? intval(wp_unslash($_POST['number_of_results'])) : '10';
    $no_result_text   = isset($_POST['no_result_text']) ? sanitize_text_field(wp_unslash($_POST['no_result_text'])) : 'No result found';
    $orderby       = isset($_POST['orderby']) ? sanitize_text_field(wp_unslash($_POST['orderby'])) : 'post_date';
    $order         = isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : 'DESC';

    // convert $display_fields to array
    $whitelisted_display_fields = array('title', 'date', 'excerpt', 'featured_image', 'product_price');
    $display_fields = explode(',', $display_fields);
    foreach ($display_fields as $key => $display_field) {
      if (!in_array($display_field, $whitelisted_display_fields, true)) {
        unset($display_fields[$key]);
      }
    }

    if (empty($display_fields)) {
      $display_fields = array('title');
    }

    $search_results = new WP_Query(
      array(
        's'         => $search_term,
        'post_type'     => $post_types,
        'posts_per_page'  => $number_of_results,
        'order'       => $order,
        'orderby'       => $orderby,
      )
    );

    echo '<div class="ism_ajax_search_results">';

    if ($search_results->have_posts()) {
      while ($search_results->have_posts()) {
        $search_results->the_post();
        echo '<div class="ism_ajax_search_item">';
        echo '<div class="ism_ajax_search_item_title"><a target="_blank" rel="noopener noreferrer" href="' . get_post_permalink() . '">' . get_the_title() . '</a></div>';
        echo '<div class="ism_ajax_search_item_date">' . get_the_date($date_format) . '</div>';
        echo '<div class="ism_ajax_search_item_excerpt">' . wp_strip_all_tags(strip_shortcodes(get_the_excerpt($post_id))) . '</div>';
        echo '<div class="foda">foda</div>';
        echo '</div>';
      }
    } else {
      echo '<p>' . esc_html($no_result_text) . '</p>';
    }

    echo '</div>';

    wp_reset_postdata();
    wp_die();
  }
}

new ISM_IdeaSciModules;
