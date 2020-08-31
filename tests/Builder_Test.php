<?php
/**
 * Builder Tests.
 *
 * @package HAMWORKS\WP\Taxonomy
 */

use HAMWORKS\WP\Taxonomy\Builder;

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
}
