<?php

class CloneTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {}

	public function testCloneTablesAreCreated() {
		global $wpdb;

		DSP_DatabaseHandler::save_tables();
		DSP_DatabaseHandler::clone_defaults( 'wp_dummy_' );

		$result = $wpdb->get_results( "SHOW TABLES LIKE '%wp_dummy_%';" );

		$this->assertEquals( count( DSP_DatabaseHandler::$all_tables ), count( $result ) );

		// Cleanup created tables
		foreach ( $result as $table ) {
			$table = (array) $table;
			$table_name = reset( $table );
			$wpdb->query( "DROP TABLE {$table_name};" );
		}
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
