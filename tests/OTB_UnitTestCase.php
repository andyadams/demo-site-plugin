<?php

abstract class OTB_UnitTestCase extends WP_UnitTestCase {
	protected $post_fixtures, $plugin_file;

	public function setUp() {
		global $wpdb;

		$i_love_demo_sites = array(
			'post_title' => 'I love demo sites!',
			'post_status' => 'publish'
		);

		$this->post_fixtures = array(
			'i_love_demo_sites' => $i_love_demo_sites
		);

		$wpdb->set_prefix( 'wp_' );

		$this->plugin_file = realpath( dirname( __FILE__ ) . '/../demo_site_plugin.php' );

		parent::setUp();
	}

	protected function assertObjectEqualsArrayForExistingKeys( $object, $array ) {
		// Convert object to array, and filter keys that are not in both arrays
		$object_to_array = array_intersect_key( (array) $object, $array );

		$this->assertEquals( $object_to_array, $array );
	}
}