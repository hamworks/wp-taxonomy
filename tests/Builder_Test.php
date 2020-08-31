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
}
