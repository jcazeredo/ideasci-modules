<?php
class ISM_IdeaSciModules extends DiviExtension {

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
	public function __construct( $name = 'ideasci-modules', $args = array() ) {
		$this->plugin_dir              = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url          = plugin_dir_url( $this->plugin_dir );
		$this->_frontend_js_data 	= array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		);
		$this->_builder_js_data        = array(
			'i10n' => array(
				'ism_cta_all_options' => array(
					'basic_fields'         => esc_html__( 'Basic Fields', 'ism-ideasci-modules' ),
					'text'                 => esc_html__( 'Text', 'ism-ideasci-modules' ),
					'textarea'             => esc_html__( 'Textarea', 'ism-ideasci-modules' ),
					'select'               => esc_html__( 'Select', 'ism-ideasci-modules' ),
					'toggle'               => esc_html__( 'Toggle', 'ism-ideasci-modules' ),
					'multiple_buttons'     => esc_html__( 'Multiple Buttons', 'ism-ideasci-modules' ),
					'multiple_checkboxes'  => esc_html__( 'Multiple Checkboxes', 'ism-ideasci-modules' ),
					'input_range'          => esc_html__( 'Input Range', 'ism-ideasci-modules' ),
					'input_datetime'       => esc_html__( 'Input Date Time', 'ism-ideasci-modules' ),
					'input_margin'         => esc_html__( 'Input Margin', 'ism-ideasci-modules' ),
					'checkboxes_category'  => esc_html__( 'Checkboxes Category', 'ism-ideasci-modules' ),
					'select_sidebar'       => esc_html__( 'Select Sidebar', 'ism-ideasci-modules' ),
					'code_fields'          => esc_html__( 'Code Fields', 'ism-ideasci-modules' ),
					'codemirror'           => esc_html__( 'Codemirror', 'ism-ideasci-modules' ),
					'prop_value'           => esc_html__( 'Prop value: ', 'ism-ideasci-modules' ),
					'rendered_prop_value'  => esc_html__( 'Rendered prop value: ', 'ism-ideasci-modules' ),
					'form_fields'          => esc_html__( 'Form Fields', 'ism-ideasci-modules' ),
					'option_list'          => esc_html__( 'Option List', 'ism-ideasci-modules' ),
					'option_list_checkbox' => esc_html__( 'Option List Checkbox', 'ism-ideasci-modules' ),
					'option_list_radio'    => esc_html__( 'Option List Radio', 'ism-ideasci-modules' ),
					'typography_fields'    => esc_html__( 'Typography Fields', 'ism-ideasci-modules' ),
					'select_font_icon'     => esc_html__( 'Select Font Icon', 'ism-ideasci-modules' ),
					'select_text_align'    => esc_html__( 'Select Text Align', 'ism-ideasci-modules' ),
					'select_font'          => esc_html__( 'Select Font', 'ism-ideasci-modules' ),
					'color_fields'         => esc_html__( 'Color Fields', 'ism-ideasci-modules' ),
					'color'                => esc_html__( 'Color', 'ism-ideasci-modules' ),
					'color_alpha'          => esc_html__( 'Color Alpha', 'ism-ideasci-modules' ),
					'upload_fields'        => esc_html__( 'Upload Fields', 'ism-ideasci-modules' ),
					'upload'               => esc_html__( 'Upload', 'ism-ideasci-modules' ),
					'advanced_fields'      => esc_html__( 'Advanced Fields', 'ism-ideasci-modules' ),
					'tab_1_text'           => esc_html__( 'Tab 1 Text', 'ism-ideasci-modules' ),
					'tab_2_text'           => esc_html__( 'Tab 2 Text', 'ism-ideasci-modules' ),
					'presets_shadow'       => esc_html__( 'Presets Shadow', 'ism-ideasci-modules' ),
					'preset_affected_1'    => esc_html__( 'Preset Affected 1', 'ism-ideasci-modules' ),
					'preset_affected_2'    => esc_html__( 'Preset Affected 2', 'ism-ideasci-modules' ),
				),
			),
		);

		parent::__construct( $name, $args );

		add_action( 'wp_ajax_my_search_query', 'my_search_query' );
		add_action( 'wp_ajax_nopriv_my_search_query', 'my_search_query' );

	}

	function my_search_query() {
		$query = $_POST['query'];
		$search_results = new WP_Query( array(
		  's' => $query,
		  'post_type' => 'post',
		  'posts_per_page' => 10
		) );
		if ( $search_results->have_posts() ) {
		  while ( $search_results->have_posts() ) {
			$search_results->the_post();
			echo '<p><a href="' . get_permalink() . '">' . get_the_title() . '</a></p>';
		  }
		} else {
		  echo '<p>No results found.</p>';
		}
		wp_reset_postdata();
		wp_die();
	  }
}

new ISM_IdeaSciModules;
