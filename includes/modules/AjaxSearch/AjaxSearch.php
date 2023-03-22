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
	 * Module's specific fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_fields() {
		return array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'ism-ideasci-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as title.', 'ism-ideasci-modules' ),
				'toggle_slug'     => 'main_content',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'ism-ideasci-modules' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'ism-ideasci-modules' ),
				'toggle_slug'     => 'main_content',
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
		$title                 = $this->props['title'];
		
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC',
		);
		
		$query = new WP_Query($args);
		
		$output = '<div class="search-results-wrap">';
		
		if ( $query->have_posts() ) {
			$output .= '<ul>';
		
			while ( $query->have_posts() ) {
				$query->the_post();
		
				$output .= '<li>';
				$output .= '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
				$output .= ' - ';
				$output .= esc_html( get_the_date("D, Y") );
				$output .= '</li>';
			}
		
			$output .= '</ul>';
		
			wp_reset_postdata();
		}

		$output .= '</div>';
		
		wp_reset_postdata();

		// $search_results = sprintf(
		// 	'<h2 class="ism-title">%1$s</h2>
		// 	<div class="ism-content">%2$s</div>',
		// 	esc_html( $title ),
		// 	et_sanitized_previously( $this->content ),
		// );

		// 3rd party module with full VB support doesn't need to manually wrap its module. Divi builder
		// has automatically wrapped it
		return $output;
	}
}

new ISM_Ajax_Search;
