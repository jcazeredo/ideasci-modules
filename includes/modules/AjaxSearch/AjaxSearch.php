<?php
/**
 * Basic Call To Action module (title, content, and button) with FULL builder support
 * This module appears on Visual Builder and requires react component to be provided
 * Due to full builder support, all advanced options (except button options) are added by default
 *
 * @since 1.0.0
 */
class ISM_Ajax_Search extends ET_Builder_Module {

	// Module slug (also used as shortcode tag)
	public $slug       = 'ism_ajax_search';

	// Visual Builder support (off|partial|on)
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://github.com/jcazeredo/ideasci-modules',
		'author'     => 'Idea-sci',
		'author_uri' => 'https://www.idea-sci.com/',
	);
	
	/**
	 * Module properties initialization
	 *
	 * @since 1.0.0
	 */
	function init() {
		// Module name
		$this->name             = esc_html__( 'Idea-sci search', 'ism-ideasci-modules' );
		$this->main_css_element = '%%order_class%%';

		// Module Icon
		// Load customized svg icon and use it on builder as module icon. If you don't have svg icon, you can use
		// $this->icon for using etbuilder font-icon. (See CustomCta / ISM_CTA class)
		$this->icon_path        =  plugin_dir_path( __FILE__ ) . 'icon.svg';

		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'ism-ideasci-modules' ),
				),
			),
		);
	}

	/**
	 * Module's toggles names
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => array(
						'title' => esc_html__( 'Configuration', 'ism-ideasci-modules' ),
					),
					'search_area' => array(
						'title' => esc_html__( 'Search Area', 'ism-ideasci-modules' ),
					),
					'display' => array(
						'title' => esc_html__( 'Display', 'ism-ideasci-modules' ),
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'search_field_settings' => array(
						'title' => esc_html__( 'Search Field', 'ism-ideasci-modules' ),
					),
					'item_settings' => array(
						'title' => esc_html__( 'Item', 'ism-ideasci-modules' ),
					),
					'search_icon_settings' => array(
						'title' => esc_html__( 'Search Icon', 'ism-ideasci-modules' ),
					),
					'loader_settings' => array(
						'title' => esc_html__( 'Loader', 'ism-ideasci-modules' ),
					),
					'search_result_item_text_settings' => array(
						'title' => esc_html__( 'Search Result Item Text', 'ism-ideasci-modules' ),
						'sub_toggles'   => array(
                            'title' => array(
                                'name' => 'Title',
                            ),
							'date' => array(
                                'name' => 'Date',
                            ),
                            'excerpt' => array(
                                'name' => 'Excerpt',
                            ),
                        ),
                        'tabbed_subtoggles' => true,
					),
				),
			),
		);
	}

	/**
	 * Module's advanced options configuration
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'search_result_item_title' => array(
					'label'          => esc_html__( 'Title', 'ism-ideasci-modules' ),
					'font_size'      => array(
						'default_on_front' => '16px',
						'range_settings'   => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'line_height'    => array(
						'default_on_front' => '1.2em',
						'range_settings'   => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default_on_front' => '0px',
						'range_settings'   => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'hide_text_align' => true,
					'css'            => array(
						'main' => '%%order_class%% .ism_ajax_search_item .ism_ajax_search_item_title',
					),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'search_result_item_text_settings',
					'sub_toggle' => 'title',
				),
				'search_result_item_date' => array(
					'label'          => esc_html__( 'Date', 'ism-ideasci-modules' ),
					'font_size'      => array(
						'default_on_front' => '14px',
						'range_settings'   => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'line_height'    => array(
						'default_on_front' => '1.5em',
						'range_settings'   => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default_on_front' => '0px',
						'range_settings'   => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'hide_text_align' => true,
					'css'            => array(
						'main' => '%%order_class%% .ism_ajax_search_item .ism_ajax_search_item_date',
					),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'search_result_item_text_settings',
					'sub_toggle' => 'date',
				),
				'search_result_item_excerpt' => array(
					'label'          => esc_html__( 'Excerpt', 'ism-ideasci-modules' ),
					'font_size'      => array(
						'default_on_front' => '14px',
						'range_settings'   => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'line_height'    => array(
						'default_on_front' => '1.5em',
						'range_settings'   => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default_on_front' => '0px',
						'range_settings'   => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'    => true,
					),
					'hide_text_align' => true,
					'css'            => array(
						'main' => '%%order_class%% .ism_ajax_search_item .ism_ajax_search_item_excerpt',
					),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'search_result_item_text_settings',
					'sub_toggle' => 'excerpt',
				),
			),
			'form_field' => array(
				'form_field' => array(
					'label' => esc_html__( 'Field', 'ism-ideasci-modules' ),
					'css' => array(
						'main' 			=> '%%order_class%% .ism_ajax_search_field_wrap input',
						'hover' 		=> '%%order_class%% .ism_ajax_search_field_wrap input:hover',
						'focus' 		=> '%%order_class%% .ism_ajax_search_field_wrap input:focus',
						'focus_hover' 	=> '%%order_class%% .ism_ajax_search_field_wrap input:focus:hover',
					),
					'font_field' => array(
						'css' => array(
							'main' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_field_wrap input',
								'%%order_class%% .ism_ajax_search_field_wrap input::placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-moz-placeholder',
							) ),
							'hover' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_field_wrap input:',
								'%%order_class%% .ism_ajax_search_field_wrap input::placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-moz-placeholder',
							) ),
							'focus' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_field_wrap input',
								'%%order_class%% .ism_ajax_search_field_wrap input::placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-moz-placeholder',
							) ),
							'focus_hover' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_field_wrap input',
								'%%order_class%% .ism_ajax_search_field_wrap input::placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_field_wrap input::-moz-placeholder',
							) ),
							'placeholder' => true,
						),
						'line_height'    => array(
							'default' => '1em',
						),
						'font_size'      => array(
							'default' => '14px',
						),
						'letter_spacing' => array(
							'default' => '0px',
						),
					),
					'margin_padding' => array(
						'use_margin' => false,
						'css'        => array(
							'padding' => '%%order_class%% .ism_ajax_search_field_wrap input',
						),
					),
					'box_shadow' => false,
					'border_styles' => array(
						'form_field' => array(
							'fields_after' => array(
								'use_focus_border_color' => false,
							),
							'css' => array(
								'main' => array(
									'border_radii'  => '%%order_class%% .ism_ajax_search_field_wrap input',
									'border_styles' => '%%order_class%% .ism_ajax_search_field_wrap input',
								),
								'important' => 'all',
							),
							'label_prefix' => esc_html__( 'Field', 'ism-ideasci-modules' ),
						),
					),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'search_field_settings',
				),
			),
			'form_field' => array(
				'form_field' => array(
					'label' => esc_html__( 'Item', 'ism-ideasci-modules' ),
					'css' => array(
						'main' 			=> '%%order_class%% .ism_ajax_search_item',
						'hover' 		=> '%%order_class%% .ism_ajax_search_item:hover',
						'focus' 		=> '%%order_class%% .ism_ajax_search_item:focus',
						'focus_hover' 	=> '%%order_class%% .ism_ajax_search_item:focus:hover',
					),
					'font_field' => array(
						'css' => array(
							'main' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_item',
								'%%order_class%% .ism_ajax_search_item::placeholder',
								'%%order_class%% .ism_ajax_search_item::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-moz-placeholder',
							) ),
							'hover' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_item:',
								'%%order_class%% .ism_ajax_search_item::placeholder',
								'%%order_class%% .ism_ajax_search_item::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-moz-placeholder',
							) ),
							'focus' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_item',
								'%%order_class%% .ism_ajax_search_item::placeholder',
								'%%order_class%% .ism_ajax_search_item::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-moz-placeholder',
							) ),
							'focus_hover' => implode(', ', array(
								'%%order_class%% .ism_ajax_search_item',
								'%%order_class%% .ism_ajax_search_item::placeholder',
								'%%order_class%% .ism_ajax_search_item::-webkit-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-ms-input-placeholder',
								'%%order_class%% .ism_ajax_search_item::-moz-placeholder',
							) ),
							'placeholder' => true,
						),
						'line_height'    => array(
							'default' => '1em',
						),
						'font_size'      => array(
							'default' => '14px',
						),
						'letter_spacing' => array(
							'default' => '0px',
						),
					),
					'margin_padding' => array(
						'use_margin' => false,
						'css'        => array(
							'padding' => '%%order_class%% .ism_ajax_search_item',
						),
					),
					'box_shadow' => false,
					'border_styles' => array(
						'form_field' => array(
							'fields_after' => array(
								'use_focus_border_color' => false,
							),
							'css' => array(
								'main' => array(
									'border_radii'  => '%%order_class%% .ism_ajax_search_item',
									'border_styles' => '%%order_class%% .ism_ajax_search_item',
								),
								'important' => 'all',
							),
							'labism_prefix' => esc_html__( 'Field', 'ism-ideasci-modules' ),
						),
					),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'item_settings',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'search_result_margin_padding' => array(
                'search_result_box' => array(
                    'margin_padding' => array(
                        'css' => array(
                        	'use_margin' => false,
                            'padding'    => '%%order_class%% .ism_ajax_search_results',
                            'important'  => 'all',
                        ),
                    ),
                ),
            ),
			'max_width' => array(
				'default' => array(
					'css' => array(
						'main'             => '%%order_class%%',
						'module_alignment' => '%%order_class%%',
					),
				),
			),
			'height' => array(
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				),
			),
			'borders' => array(
				'search_result_box' => array(
					'labism_prefix' => 'Result Box',
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .ism_ajax_search_results',
							'border_styles' => '%%order_class%% .ism_ajax_search_results',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'border',
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%%',
							'border_radii'  => '%%order_class%%',
						),
					),
				),
			),
			'box_shadow' => array(
				'search_result_box' => array(
					'label'       => esc_html__( 'Result Box Box Shadow', 'ism-ideasci-modules' ),
					'css'         => array(
						'main' => '%%order_class%% .ism_ajax_search_results',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				),
			),
			'filters' => false,
			'text' => false,
			'link_options'  => false,
		);
	}

	/**
	 * Module's specific fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_fields() {
		$raw_post_types = get_post_types( array(
			'public' => true,
			'show_ui' => true,
			'exclude_from_search' => false,
		), 'objects' );

		$blocklist = array( 'et_pb_layout', 'layout', 'attachment' );

		$post_types = array();

		foreach ( $raw_post_types as $post_type ) {
			$is_blocklisted = in_array( $post_type->name, $blocklist );
			
			if ( ! $is_blocklisted && post_type_exists( $post_type->name ) ) {
				if ( isset( $post_type->label ) ) {
					$label = esc_html( $post_type->label );
				} else if ( isset( $post_type->labels->name ) ) {
					$label = esc_html( $post_type->labels->name );
				} else if ( isset( $post_type->labels->singular_name ) ) {
					$label = esc_html( $post_type->labels->singular_name );
				} else {
					$label = esc_html( $post_type->name );
				}
				$slug  	= sanitize_text_field( $post_type->name );
				$post_types[$slug] = esc_html( $label );
			}
		}

		if ( ! empty( $post_types ) ) {
			$post_types['all'] = esc_html__( 'All of the above', 'ism-ideasci-modules' );
		}
		
		$display_fields = array( 
			'title' => esc_html__( 'Title', 'ism-ideasci-modules' ),
			'date' => esc_html__( 'Date', 'ism-ideasci-modules' ),
			'excerpt' => esc_html__( 'Excerpt', 'ism-ideasci-modules' ),
		);

		return array(
			'search_placeholder' => array(
				'label'           		=> esc_html__( 'Search Field Placeholder', 'ism-ideasci-modules' ),
				'type'           		=> 'text',
				'option_category' 		=> 'basic_option',
				'default_on_front' 		=> esc_html__( 'Search', 'ism-ideasci-modules' ),
				'default'		   		=> esc_html__( 'Search', 'ism-ideasci-modules' ),
				'tab_slug'        		=> 'general',
				'toggle_slug'     		=> 'main_content',
				'description'     		=> esc_html__( 'Here you can input the placeholder to be used for the search field.', 'ism-ideasci-modules' ),
			),
			'number_of_results' => array(
				'label'           		=> esc_html__( 'Search Result Number', 'ism-ideasci-modules' ),
				'type'           		=> 'text',
				'option_category' 		=> 'basic_option',
				'default'		   		=> '10',
				'tab_slug'        		=> 'general',
				'toggle_slug'     		=> 'main_content',
				'description'     		=> esc_html__( 'Here you can input the number of items to be displayed in the search result. Input -1 for all.', 'ism-ideasci-modules' ),
			),
			'orderby' => array(
				'label'            => esc_html__( 'Order by', 'ism-ideasci-modules' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'post_date' 	=> esc_html__( 'Date', 'ism-ideasci-modules' ),
					'post_modified'	=> esc_html__( 'Modified Date', 'ism-ideasci-modules' ),
					'post_title'   	=> esc_html__( 'Title', 'ism-ideasci-modules' ),
					'post_name'     => esc_html__( 'Slug', 'ism-ideasci-modules' ),
					'ID'       		=> esc_html__( 'ID', 'ism-ideasci-modules' ),
					'rand'     		=> esc_html__( 'Random', 'ism-ideasci-modules' ),
				),
				'default'          => 'post_date',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can choose the order type of your results.', 'ism-ideasci-modules' ),
			),
			'order' => array(
				'label'            => esc_html__( 'Order', 'ism-ideasci-modules' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'DESC' => esc_html__( 'DESC', 'ism-ideasci-modules' ),
					'ASC'  => esc_html__( 'ASC', 'ism-ideasci-modules' ),
				),
				'default'          => 'DESC',
				'show_if_not'      => array(
					'orderby' => 'rand',
				),
				'tab_slug'         => 'general',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can choose the order of your results.', 'ism-ideasci-modules' ),
			),
			'no_result_text' => array(
				'label'           		=> esc_html__( 'No Result Text', 'ism-ideasci-modules' ),
				'type'           		=> 'text',
				'option_category' 		=> 'basic_option',
				'default'		   		=> esc_html__( 'No results found', 'ism-ideasci-modules' ),
				'tab_slug'        		=> 'general',
				'toggle_slug'     		=> 'main_content',
				'description'     		=> esc_html__( 'Here you can input the custom text to be displayed when no results found.', 'ism-ideasci-modules' ),
			),
			'include_post_types' => array(
				'label'            		=> esc_html__( 'Post Types', 'ism-ideasci-modules' ),
				'type'             		=> 'select',
				'option_category'  		=> 'basic_option',
				'options'				=> $post_types,
				'default'				=> 'post',
				'tab_slug'         		=> 'general',
				'toggle_slug'      		=> 'search_area',
				'description'      		=> esc_html__( 'Here you can choose which post types you would like to search in.', 'ism-ideasci-modules' ),
			),
			'show_search_icon' => array(
				'label'            		=> esc_html__( 'Show Search Icon', 'ism-ideasci-modules' ),
				'type'             		=> 'yes_no_button',
				'option_category'  		=> 'configuration',
				'options'          		=> array(
					'on'  => esc_html__( 'Yes', 'ism-ideasci-modules' ),
					'off' => esc_html__( 'No', 'ism-ideasci-modules' ),
				),
				'default'          		=> 'on',
				'tab_slug'         		=> 'general',
				'toggle_slug'      		=> 'display',
				'description'      		=> esc_html__( 'This will turn the search icon on and off.', 'ism-ideasci-modules' ),
			),
			'display_fields' => array(
				'label'            		=> esc_html__( 'Display Fields', 'ism-ideasci-modules' ),
				'type'             		=> 'multiple_checkboxes',
				'option_category'  		=> 'basic_option',
				'options'				=> $display_fields,
				'default'				=> 'on|on|on',
				'tab_slug'         		=> 'general',
				'toggle_slug'      		=> 'display',
				'description'      		=> esc_html__( 'Here you can choose which fields you would like to display in search results.', 'ism-ideasci-modules' ),
			),
			'date_format' => array(
				'label'           		=> esc_html__( 'Date Format', 'ism-ideasci-modules' ),
				'type'           		=> 'text',
				'option_category' 		=> 'basic_option',
				'default'		   		=> 'M, Y',
				'tab_slug'        		=> 'general',
				'toggle_slug'     		=> 'display',
				'description'     		=> esc_html__( 'If you would like to adjust the date format, input the appropriate PHP date format here.', 'ism-ideasci-modules' ),
			),
		);
	}

	public function process_multiple_checkboxes_value( $value, $values = array() ) {
		if ( empty( $values ) && ! is_array( $values ) ) {
			return '';
		}
		
		$new_values = array();
		$value 		= explode( '|', $value );
		foreach( $value as $key => $val ) {
			if ( 'on' === strtolower( $val ) ) {
				array_push( $new_values, $values[$key] );
			}
		}
		return implode( ',', $new_values );
	}

	/**
	 * Render module output
	 *
	 * @since 1.0.0
	 *
	 * @param array  $attrs       List of unprocessed attributes
	 * @param string $content     Content being processed
	 * @param string $render_slug Slug of module that is used for rendering output
	 *
	 * @return string module's rendered output
	 */
	function render( $attrs, $content = null, $render_slug ) {

		// Module specific props added on $this->get_fields()
		$search_placeholder 	= $this->props['search_placeholder'];
		$orderby				= $this->props['orderby'];
		$order 					= $this->props['order'];
		$include_post_types 	= $this->props['include_post_types'];
		$show_search_icon		= $this->props['show_search_icon'];
		$display_fields			= $this->props['display_fields'];
		$date_format			= $this->props['date_format'];
		$number_of_results		= $this->props['number_of_results'];
		$no_result_text			= $this->props['no_result_text'];
		
		$whitelisted_display_fields = array( 'title', 'date', 'excerpt', );
		$display_fields = $this->process_multiple_checkboxes_value( $display_fields, $whitelisted_display_fields );
		
		$search_icon = '<span class="ism_ajax_search_search_icon">
							<span class="et-pb-icon">&#x55;</span>
						</span>';

		$search_field_wrap = sprintf(
			'<div class="ism_ajax_search_field_wrap">
				<input type="search" placeholder="%1$s" class="ism_ajax_search_field" id="ism_ajax_search_field" data-search-post-type="%2$s" data-display-fields="%3$s" data-number-of-results="%4$s" data-no-result-text="%5$s" data-orderby="%6$s" data-order="%7$s" data-date-format="%8$s" />
				%9$s
			</div>
			<div class="ism_ajax_search_results_wrap"></div>',
			esc_attr( $search_placeholder ),
			esc_attr( $include_post_types ),
			esc_attr( $display_fields ),
			intval( $number_of_results ),
			esc_attr( $no_result_text ),
			esc_attr( $orderby ),
			esc_attr( $order ),
			esc_attr( $date_format ),
			'on' === $show_search_icon ? $search_icon : '',
		);
		
		return $search_field_wrap;
	}
}

new ISM_Ajax_Search;
