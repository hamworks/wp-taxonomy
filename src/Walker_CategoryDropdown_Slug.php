<?php

namespace HAMWORKS\WP\Taxonomy;

/**
 * Class Walker_CategoryDropdown_Slug
 *
 * @package Torounit\WP
 */
class  Walker_CategoryDropdown_Slug extends \Walker_CategoryDropdown {

	/**
	 * Start element.
	 *
	 * @param string $output output
	 * @param object $category terms
	 * @param int $depth depth
	 * @param array $args arguments
	 * @param int $id id
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat( '&nbsp;', $depth * 3 );
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', $category->name, $category );
		$output  .= '<option class="level-' . esc_attr( $depth ) . '" value="' . esc_attr( $category->slug ) . '"';
		if ( $category->slug === $args['selected'] ) {
			$output .= ' selected="selected"';
		}
		$output .= '>';
		$output .= $pad . $cat_name;
		if ( $args['show_count'] ) {
			$output .= '&nbsp;&nbsp;(' . number_format_i18n( $category->count ) . ')';
		}
		$output .= '</option>';
	}
}
