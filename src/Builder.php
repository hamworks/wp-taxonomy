<?php
/**
 * Package for Taxonomy.
 *
 * @package HAMWORKS\WP
 */

namespace HAMWORKS\WP\Taxonomy;

use Doctrine\Inflector\InflectorFactory;

/**
 * Taxonomy Builder class.
 */
class Builder {

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 */
	private $label;

	/**
	 * Post types.
	 *
	 * @var array
	 */
	private $post_type;

	/**
	 * Options.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Default terms.
	 *
	 * @var Term[]
	 */
	private $initial_terms = array();

	/**
	 * Labels.
	 *
	 * @var array
	 */
	private $labels;

	/**
	 * Constructor.
	 *
	 * @param string       $name taxonomy slug.
	 * @param string       $label taxonomy name.
	 * @param array|string $post_type post type.
	 */
	public function __construct( $name, $label, $post_type = array( 'post' ) ) {
		$this->name      = $name;
		$this->label     = $label;
		$this->post_type = (array) $post_type;
		$this->set_labels();
		$this->set_options();
	}

	/**
	 * Add hooks.
	 */
	public function create() {
		$this->register_taxonomy();
		$this->initialize_taxonomy();
		if ( ! empty( $this->args['show_admin_column'] ) ) {
			new Admin_Dropdown( $this->name, $this->post_type );
		}
	}

	/**
	 * Getter
	 *
	 * @return \WP_Taxonomy|false
	 */
	public function get_taxonomy() {
		return \get_taxonomy( $this->name );
	}

	/**
	 * Setter options.
	 *
	 * @param array $args options.
	 */
	public function set_options( array $args = array() ) {
		$this->args = $this->create_options( $args );
	}

	/**
	 * Option builder.
	 *
	 * @param array $args options.
	 *
	 * @return array
	 */
	private function create_options( array $args = array() ) {
		$inflector      = InflectorFactory::create()->build();
		$singular_slug  = $inflector->urlize( $this->name );
		$pluralize_slug = $inflector->pluralize( $singular_slug );

		$defaults = array(
			'show_in_rest'      => true,
			'rest_base'         => $pluralize_slug,
			'show_admin_column' => true,
			'rewrite'           => array(
				'with_front' => false,
				'slug'       => $singular_slug,
				'walk_dirs'  => false,
			),
		);

		return array_merge( $defaults, $args );
	}


	/**
	 * Setter Labels.
	 *
	 * @param array $args label dictionary.
	 */
	public function set_labels( $args = array() ) {
		$this->labels = $this->create_labels( $args );
	}

	/**
	 * Label builder.
	 *
	 * @param array $args labels.
	 *
	 * @return array
	 */
	private function create_labels( array $args = array() ) {
		$defaults = array(
			'name'                => $this->label,
			'singular_name'       => $this->label,
			'search_items'        => $this->label . 'を検索',
			'popular_items'       => 'よく使う' . $this->label,
			'all_items'           => '全ての' . $this->label,
			'edit_item'           => $this->label . 'を編集',
			'update_item'         => $this->label . 'を更新',
			'add_new_item'        => $this->label . 'を追加',
			'new_item_name'       => '新しい' . $this->label,
			'add_or_remove_items' => $this->label . 'を追加もしくは削除',
			'menu_name'           => $this->label,
		);

		return array_merge( $defaults, $args );
	}

	/**
	 * Register
	 */
	private function register_taxonomy() {
		$this->args['labels'] = $this->labels;
		register_taxonomy( $this->name, $this->post_type, $this->args );
	}

	/**
	 * Add default terms.
	 */
	private function initialize_taxonomy() {
		if ( ! empty( $this->initial_terms ) ) {
			foreach ( $this->initial_terms as $term ) {
				$this->insert_initial_term( $term );
			}
		}
	}

	/**
	 * Insert term if default term not exist.
	 *
	 * @param Term $term Term options.
	 */
	private function insert_initial_term( $term ) {
		if ( ! term_exists( $term->name, $this->name ) ) {
			$args = (array) $term;
			if ( ! empty( $term->parent ) && is_string( $term->parent ) ) {
				$term_id = term_exists( $term->parent, $this->name );
				if ( is_array( $term_id ) ) {
					$term_id = $term_id['term_id'];
				}
				$args['parent'] = absint( $term_id );
			}
			wp_insert_term( $term->name, $this->name, $args );
		}
	}

	/**
	 * Create Default Term
	 *
	 * @param Term $term term entity.
	 */
	public function set_initial_term( Term $term ) {
		$this->initial_terms[ $term->slug ] = $term;
	}

}
