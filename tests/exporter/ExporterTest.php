<?php

class ExporterTest extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function testExportSinglePost() {
		global $wpdb;

		$wpdb->query( "DELETE FROM wp_posts;" );

		$sample_post = array(
			'post_title' => 'I love demo sites!'
		);

		$result = wp_insert_post( $sample_post );

		demo_site_plugin_export_posts();

		$posts = unserialize( get_option( 'demo_site_plugin_posts_table_default' ) );

		$this->assertObjectEqualsArrayForExistingKeys( $posts[0], $sample_post );
	}

	protected function assertObjectEqualsArrayForExistingKeys( $object, $array ) {
		// Convert object to array, and filter keys that are not in both arrays
		$object_to_array = array_intersect_key( (array) $object, $array );

		$this->assertEquals( $object_to_array, $array );
	}
}
