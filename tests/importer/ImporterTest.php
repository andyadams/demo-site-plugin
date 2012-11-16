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

		DSP_DatabaseHandler::save_tables( $wpdb->prefix, array( 'posts' ) );

		$wpdb->query( "DELETE FROM wp_posts;" );

		DSP_DatabaseHandler::reset_tables( $wpdb->prefix, array( 'posts' ) );

		$all_posts = get_posts();

		$this->assertObjectEqualsArrayForExistingKeys( $all_posts[0], $sample_post );
	}
}
