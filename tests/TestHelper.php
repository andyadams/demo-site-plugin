<?php

abstract class OTB_UnitTestCase extends WP_UnitTestCase {
	protected $post_fixtures;

	public function setUp() {
		$i_love_demo_sites = array(
			'post_title' => 'I love demo sites!',
			'post_status' => 'publish'
		);

		$this->post_fixtures = array(
			'i_love_demo_sites' => $i_love_demo_sites
		);

		parent::setUp();
	}

	protected function assertObjectEqualsArrayForExistingKeys( $object, $array ) {
		// Convert object to array, and filter keys that are not in both arrays
		$object_to_array = array_intersect_key( (array) $object, $array );

		$this->assertEquals( $object_to_array, $array );
	}
}