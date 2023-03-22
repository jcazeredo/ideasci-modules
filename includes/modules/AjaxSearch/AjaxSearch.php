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

	/**
	 * Module properties initialization
	 *
	 * @since 1.0.0
	 */
	function init() {
		// Module name
		$this->name             = esc_html__( 'Idea-sci search', 'ism-ideasci-modules' );

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

	/**
	 * Module's advanced options configuration
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_advanced_fields_config() {
		return array();
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
		$display_fields			= $this->props['display_fields'];
		$date_format			= $this->props['date_format'];
		$number_of_results		= $this->props['number_of_results'];
		$no_result_text			= $this->props['no_result_text'];
		
		$args = array(
			'post_type' => $include_post_types,
			'posts_per_page' => $number_of_results,
			'orderby' => $orderby,
			'order' => $order,
		);
		
		$query = new WP_Query($args);
		
		$output = '<div class="ism-ajax-search">';
		$output .= '<div class="ism-ajax-search-wrap">';

		if ( $query->have_posts() ) {
			
		
			while ( $query->have_posts() ) {
				$query->the_post();
				
				$output .= sprintf( 
					'<li><a href="%s">%s - %s</a></li>',
					esc_url( get_permalink() ),
					esc_html( get_the_title() ), 
					esc_html( get_the_date($date_format) ) 
				);
			}
		
			$output .= '</div>';
		
			wp_reset_postdata();
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

new ISM_Ajax_Search;
