<?php

class DemoLoginTest extends OTB_UnitTestCase {
	// User clicks "start demo" button
	// Token is created for the user
	// Tables are created with prefix wp_{$token}_
	// Semi-admin account is created
	// User is logged in as semi-admin and brought to dashboard
	// Site is accessed via modified URL /demo/{$token}/.../
	// All users visiting /demo/{$token}/.../ are logged in as semi-admin
	// All media uploaded will be stored in /uploads/demo/{$token}/.../

	public function testTokenCreated() {
		$active_demo_tokens = get_option( 'demo_site_plugin_active_demo_tokens' );

		$this->assertEmpty( $active_demo_tokens );

		demo_site_plugin_create_site_with_token( 'abcdef' );

		$active_demo_tokens = get_option( 'demo_site_plugin_active_demo_tokens' );

		$this->assertContains( 'abcdef', $active_demo_tokens );
	}

	public function testTablesCreated() {
		global $wpdb;

		$token = 'abcdef';

		demo_site_plugin_create_site_with_token( $token );

		$created_tables = $wpdb->get_results( "SHOW TABLES LIKE '%wp_{$token}_%';" );

		$this->assertEquals( count( DSP_DatabaseHandler::$all_tables ), count( $created_tables ) );

		$this->cleanupTablesWithPrefix( "wp_{$token}" );
	}
}