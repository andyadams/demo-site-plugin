<?php

class ImporterTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function testImportSinglePost() {
		global $wpdb;

		$wpdb->query( "DELETE FROM wp_posts;" );

		$sample_post = $this->post_fixtures['i_love_demo_sites'];

		$result = wp_insert_post( $sample_post );

		demo_site_plugin_save_table_defaults( 'posts' );

		$wpdb->query( "DELETE FROM wp_posts;" );

		demo_site_plugin_restore_table_to_defaults( 'posts' );

		$all_posts = get_posts();

		$this->assertObjectEqualsArrayForExistingKeys( $all_posts[0], $sample_post );
	}
}
