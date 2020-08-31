<?php

namespace HAMWORKS\WP\Taxonomy;

class Director_Test extends \WP_UnitTestCase {

	/**
	 * Test sample.
	 *
	 * @test
	 */
	public function test_exsist() {
		$this->assertTrue( class_exists( Builder::class ) );
	}
}
