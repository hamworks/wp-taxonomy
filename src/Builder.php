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
class Builder {
	/**
	 * Post types.
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
	 * @var Term[]
	 */
	private $default_terms = array();

	/**
	 * Labels.
	 *
	 * @var array
	 */
	private $labels;

	/**
	 * Constructor.
	 *
	 * @param string       $taxonomy taxonomy slug.
	 * @param string       $taxonomy_name taxonomy name.
	 * @param array|string $post_type post type.
	 */
	public function __construct( $taxonomy, $taxonomy_name, $post_type = array( 'post' ) ) {
		$this->taxonomy      = $taxonomy;
		$this->taxonomy_name = $taxonomy_name;
		$this->post_type     = (array) $post_type;
		$this->set_labels();
		$this->set_options();
	}

	/**
	 * Add hooks.
	 */
	public function create() {
		$this->register_taxonomy();
		add_action(
			'wp_loaded',
			function () {
				$this->initialize_taxonomy();
			},
			10
		);
		if ( ! empty( $this->args['show_admin_column'] ) ) {
			new Admin_Dropdown( $this->taxonomy, $this->post_type );
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
		$defaults = array(
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'with_front' => false ),
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

		return array_merge( $defaults, $args );
	}

	/**
	 * Register
	 */
	private function register_taxonomy() {
		$this->args['labels'] = $this->labels;
		register_taxonomy( $this->taxonomy, $this->post_type, $this->args );
	}

	/**
	 * Add default terms.
	 */
	private function initialize_taxonomy() {
		if ( ! empty( $this->default_terms ) ) {
			foreach ( $this->default_terms as $term ) {
				$this->insert_default_term( $term );
			}
		}
	}

	/**
	 * Insert term if default term not exist.
	 *
	 * @param Term $term Term options.
	 */
	private function insert_default_term( $term ) {
		if ( ! term_exists( $term->name, $this->taxonomy ) ) {
			wp_insert_term( $term->name, $this->taxonomy, (array) $term );
		}
	}

	/**
	 * Create Default Term
	 *
	 * @param Term $term term entity.
	 */
	public function set_default_term( Term $term ) {
		$this->default_terms[ $term->slug ] = $term;
	}

}
