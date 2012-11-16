<?php

class ExporterTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function testExportSinglePost() {
		global $wpdb;

		$wpdb->query( "DELETE FROM wp_posts;" );

		$sample_post = $this->post_fixtures['i_love_demo_sites'];

		$result = wp_insert_post( $sample_post );

		DSP_DatabaseHandler::save_tables( array( 'posts' ) );

		$posts = unserialize( get_option( 'demo_site_plugin_posts_table_defaults' ) );

		$this->assertObjectEqualsArrayForExistingKeys( $posts[0], $sample_post );
	}
}
