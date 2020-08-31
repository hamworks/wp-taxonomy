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

	/** @var array */
	private $post_type;

	/** @var string */
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
		global $post_type;
		if ( in_array( $post_type, $this->post_type ) ) {
			$dropdown_options = array(
				'show_option_all' => __( 'All categories' ),
				'hide_empty'      => 0,
				'hierarchical'    => 1,
				'name'            => $this->taxonomy,
				'show_count'      => 0,
				'orderby'         => 'name',
				'taxonomy'        => $this->taxonomy,
				'selected'        => get_query_var( $this->taxonomy ),
				'walker'          => new Walker_CategoryDropdown_Slug(),
			);
			echo '<label class="screen-reader-text" for="' . esc_attr( $this->taxonomy ) . '">' . esc_attr( __( 'Filter by category' ) ) . '</label>';
			wp_dropdown_categories( $dropdown_options );
		}
	}
}
