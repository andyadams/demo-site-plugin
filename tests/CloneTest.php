<?php

class CloneTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {}

	public function testCloneTablesAreCreated() {
		global $wpdb;

		$prefix = 'wp_dummy_';

		DSP_DatabaseHandler::save_tables();
		DSP_DatabaseHandler::clone_defaults( $prefix );

		$result = $wpdb->get_results( "SHOW TABLES LIKE '%$prefix%';" );

		$this->assertEquals( count( DSP_DatabaseHandler::$all_tables ), count( $result ) );

		$this->cleanupTablesWithPrefix( $prefix );
	}

	public function testDemoLoginRewrite() {
		$this->go_to( 'http://example.org/?demo_login=true' );

		// Need to be declared *after* go_to is called, because go_to unsets them
		global $wp_rewrite, $wp_query;

		$demo_login_query_var = $wp_query->get( 'demo_login' );

		$this->assertFalse( empty( $demo_login_query_var ) );

		// Test with pretty permalinks
		$wp_rewrite->set_permalink_structure( '/%postname%/' );

		$this->go_to( 'http://example.org/demo-login/' );

		$demo_login_query_var = $wp_query->get( 'demo_login' );

		$this->assertFalse( empty( $demo_login_query_var ) );
	}
}
