<?php
/**
 * Package for Taxonomy.
 *
 * @package HAMWORKS\WP
 */

namespace HAMWORKS\WP\Taxonomy;

class Term {

	/**
	 * Slug.
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Parent term id.
	 *
	 * @var int
	 */
	public $parent;

	/**
	 * Description.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Alias term slug.
	 *
	 * @var string
	 */
	public $alias_of;

	/**
	 * Term constructor.
	 *
	 * @param string $name Name.
	 * @param string $slug Slug.
	 * @param int|string $parent Parent term id or slug.
	 * @param string $description Description.
	 * @param string $alias_of alias slug.
	 */
	public function __construct( $name = '', $slug = '', $parent = 0, $description = '', $alias_of = '' ) {
		$this->name = $name;
		if ( empty( $slug ) ) {
			$slug = $name;
		}
		$this->slug        = $slug;
		$this->parent      = $parent;
		$this->description = $description;
		$this->alias_of    = $alias_of;
	}
}
