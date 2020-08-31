<?php
/**
 * Package for Taxonomy.
 *
 * @package HAMWORKS\WP
 */

namespace HAMWORKS\WP\Taxonomy;

/**
 * Taxonomy Builder class.
 */
class Taxonomy {
	/**
	 * Post types.
	 *
	 * @var array
	 */
	private $post_type = array();

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private $taxonomy;

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 */
	private $taxonomy_name;

	/**
	 * Options.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Default terms.
	 *
	 * @var array{slug: string, name: string, parent: int, description: string, alias_of: string}[]
	 */
	private $default_terms = array();

	/**
	 * Constructor.
	 *
	 * @param string       $taxonomy taxonomy slug.
	 * @param string       $taxonomy_name taxonomy name.
	 * @param array|string $post_type post type.
	 * @param array        $args register_taxonomy arguments.
	 */
	public function __construct( $taxonomy, $taxonomy_name, $post_type = array( 'post' ), $args = array() ) {
		$this->taxonomy      = $taxonomy;
		$this->taxonomy_name = $taxonomy_name;
		if ( is_array( $post_type ) ) {
			$this->post_type = $post_type;
		} else {
			$this->post_type = array( $post_type );
		}
		$this->set_options( $args );
		$this->register();
	}

	/**
	 * Setter options.
	 *
	 * @param array $args options.
	 */
	private function set_options( array $args ) {
		$this->args = $this->create_options( $args );
	}

	/**
	 * Add hooks.
	 */
	private function register() {
		$this->register_taxonomy();
		add_action( 'wp_loaded', array( $this, 'initialize_taxonomy' ), 10 );
		if ( ! empty( $this->args['show_admin_column'] ) ) {
			new Admin_Dropdown( $this->taxonomy, $this->post_type );
		}
	}

	/**
	 * Label builder.
	 *
	 * @return array
	 */
	private function create_labels() {
		return array(
			'name'                => $this->taxonomy_name,
			'singular_name'       => $this->taxonomy_name,
			'search_items'        => $this->taxonomy_name . 'を検索',
			'popular_items'       => 'よく使う' . $this->taxonomy_name,
			'all_items'           => '全ての' . $this->taxonomy_name,
			'edit_item'           => $this->taxonomy_name . 'を編集',
			'update_item'         => $this->taxonomy_name . 'を更新',
			'add_new_item'        => $this->taxonomy_name . 'を追加',
			'new_item_name'       => '新しい' . $this->taxonomy_name,
			'add_or_remove_items' => $this->taxonomy_name . 'を追加もしくは削除',
			'menu_name'           => $this->taxonomy_name,
		);
	}

	/**
	 * Option builder.
	 *
	 * @param array $args options.
	 *
	 * @return array
	 */
	private function create_options( array $args ) {
		$defaults = array(
			'show_in_rest'      => true,
			'labels'            => $this->create_labels(),
			'show_admin_column' => true,
			'rewrite'           => array( 'with_front' => false ),
		);

		return array_merge( $defaults, $args );
	}

	/**
	 * Register
	 */
	public function register_taxonomy() {
		register_taxonomy( $this->taxonomy, $this->post_type, $this->args );
	}

	/**
	 * Add default terms.
	 */
	public function initialize_taxonomy() {
		if ( ! empty( $this->default_terms ) ) {
			array_walk( $this->default_terms, array( $this, 'set_default_term' ) );
		}
	}

	/**
	 * Insert term if default term not exist.
	 *
	 * @param array $term Term options.
	 */
	public function set_default_term( $term ) {
		if ( ! term_exists( $term['name'], $this->taxonomy ) ) {
			wp_insert_term( $term['name'], $this->taxonomy, $term );
		}
	}

	/**
	 * Create Default Term
	 *
	 * @param string $name term name.
	 * @param string $slug term slug.
	 * @param array  $args term arguments.
	 */
	public function add_default_term( $name, $slug = '', $args = array() ) {
		if ( ! $slug ) {
			$slug = $name;
		}
		$term                  = array_merge(
			array(
				'name' => $name,
				'slug' => $slug,
			),
			$args
		);
		$this->default_terms[] = $term;
	}

	/**
	 * Create Default Terms
	 *
	 * @param array $terms term info.
	 */
	public function add_default_terms( array $terms ) {
		foreach ( $terms as $term ) {
			if ( is_string( $term ) ) {
				$this->add_default_term( $term );
			} else {
				$this->add_default_term( $term['name'], $term['slug'], $term['args'] );
			}
		}
	}

	/**
	 * Getter
	 *
	 * @return \WP_Taxonomy|false
	 */
	public function get_taxonomy() {
		return \get_taxonomy( $this->taxonomy );
	}
}
