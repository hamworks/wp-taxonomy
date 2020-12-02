<?php
/**
 * Admin filter by Term.
 *
 * @package HAMWORKS\WP
 */

namespace HAMWORKS\WP\Taxonomy;

/**
 * Class Admin_Dropdown
 */
class Admin_Dropdown {

	/**
	 * Post type names.
	 *
	 * @var array
	 */
	private $post_type;

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private $taxonomy;

	/**
	 * Filter constructor.
	 *
	 * @param string       $taxonomy taxonomy.
	 * @param array|string $post_type post type.
	 */
	public function __construct( $taxonomy, $post_type ) {
		$this->taxonomy = $taxonomy;
		if ( is_array( $post_type ) ) {
			$this->post_type = $post_type;
		} else {
			$this->post_type = array( $post_type );
		}
		add_action( 'restrict_manage_posts', array( $this, 'add_post_taxonomy_restrict_filter' ) );
	}

	/**
	 * Add Dropdown.
	 */
	public function add_post_taxonomy_restrict_filter() {
		$labels = get_taxonomy_labels( get_taxonomy( $this->taxonomy ) );
		if ( in_array( get_query_var( 'post_type' ), $this->post_type, true ) ) {
			$dropdown_options = array(
				'show_option_all' => $labels->all_items,
				'hide_empty'      => 0,
				'hierarchical'    => 1,
				'name'            => $this->taxonomy,
				'show_count'      => 0,
				'orderby'         => 'name',
				'taxonomy'        => $this->taxonomy,
				'selected'        => get_query_var( $this->taxonomy ),
				'value_field'     => 'slug',
			);
			echo '<label class="screen-reader-text" for="' . esc_attr( $this->taxonomy ) . '">' . esc_attr( sprintf( __( 'Filter by %s' ), $labels->singular_name ) ) . '</label>';
			wp_dropdown_categories( $dropdown_options );
		}
	}
}
