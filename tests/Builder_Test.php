<?php
/**
 * Builder Tests.
 *
 * @package HAMWORKS\WP\Taxonomy
 */

use HAMWORKS\WP\Taxonomy\Builder;
use HAMWORKS\WP\Taxonomy\Term;

/**
 * Class Builder_Test
 */
class Builder_Test extends \WP_UnitTestCase {

	/**
	 * Test sample.
	 *
	 * @test
	 */
	public function test_create() {
		$builder = new Builder( 'foo', 'Foo', array( 'post' ) );
		$builder->set_options(
			array(
				'public'      => true,
				'description' => 'taxonomy_for_test',
			)
		);
		$builder->create();

		$taxonomy = $builder->get_taxonomy();
		$this->assertEquals( 'foo', $taxonomy->name );
		$this->assertEquals( 'Foo', $taxonomy->label );
	}

	/**
	 * Init term test.
	 * @test
	 */
	public function test_term_exists() {
		$builder = new Builder( 'hoge', 'Hoge', array( 'post' ) );
		$builder->set_options(
			array(
				'public'      => true,
				'description' => 'taxonomy_for_test',
			)
		);
		$term_entity = new Term( 'Term', 'TERM' );
		$builder->set_initial_term( $term_entity );
		$builder->create();

		$term_id = term_exists( 'Term' );
		$term    = get_term( $term_id, 'hoge' );
		$this->assertEquals( $term->slug, 'term' );
	}

	/**
	 * Init term test.
	 * @test
	 */
	public function test_hierarchical_terms_exists() {
		$builder = new Builder( 'hierarchical', 'hierarchical', array( 'post' ) );
		$builder->set_options(
			array(
				'public'       => true,
				'hierarchical' => true,
				'description'  => 'taxonomy_for_test',
			)
		);
		$parent = new Term( 'parent', 'parent' );
		$child  = new Term( 'child', 'child', 'parent' );
		$builder->set_initial_term( $parent );
		$builder->set_initial_term( $child );
		$builder->create();

		$parent_id   = absint( term_exists( 'parent' ) );
		$parent_term = get_term( $parent_id, 'hierarchical' );
		$child_id    = absint( term_exists( 'child' ) );
		$child_term  = get_term( $child_id, 'hierarchical' );
		$this->assertEquals( $child_term->slug, 'child' );
		$this->assertEquals( $child_term->parent, $parent_term->term_id );
		$children = get_term_children( $parent_id, 'hierarchical' );
		$this->assertTrue( in_array( $child_id, $children, true ) );
	}

}
