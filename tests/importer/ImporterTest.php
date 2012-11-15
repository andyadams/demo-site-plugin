<?php

class ImporterTest extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function testImportSinglePost() {
		global $wpdb;

		$wpdb->query( "DELETE FROM wp_posts;" );

		$sample_post = array(
			'post_title' => 'I love demo sites!',
			'post_status' => 'publish'
		);

		$result = wp_insert_post( $sample_post );

		demo_site_plugin_export_posts();

		$wpdb->query( "DELETE FROM wp_posts;" );

		demo_site_plugin_import_posts();

		$all_posts = get_posts();

		$this->assertObjectEqualsArrayForExistingKeys( $all_posts[0], $sample_post );
	}

	protected function assertObjectEqualsArrayForExistingKeys( $object, $array ) {
		// Convert object to array, and filter keys that are not in both arrays
		$object_to_array = array_intersect_key( (array) $object, $array );

		$this->assertEquals( $object_to_array, $array );
	}
}
