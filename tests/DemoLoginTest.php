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

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
		$this->cleanupTablesWithPrefix( $this->prefix );
	}

	public function testTokenCreated() {
		DSP_DatabaseHandler::save_tables();

		$active_demo_tokens = get_option( 'demo_site_plugin_active_demo_tokens' );

		$this->assertEmpty( $active_demo_tokens );

		$demo_site = new DSP_DemoSite( $this->token );
		$demo_site->create();

		$active_demo_tokens = get_option( 'demo_site_plugin_active_demo_tokens' );

		$this->assertContains( $this->token, $active_demo_tokens );
	}

	public function testTablesCreated() {
		global $wpdb;

		$token = $this->token;
		$prefix = $this->prefix;

		$demo_site = new DSP_DemoSite( $token );
		$demo_site->create();

		$created_tables = $wpdb->get_results( "SHOW TABLES LIKE '%$prefix%';" );

		$this->assertEquals( count( DSP_DatabaseHandler::$all_tables ), count( $created_tables ) );
	}

	public function testSemiAdminCreated() {
		global $wpdb;

		$wpdb->set_prefix( $this->prefix );

		$token = $this->token;

		$demo_site = new DSP_DemoSite( $token );
		$demo_site->create();

		$semi_admin_users = get_users( array( 'role' => 'semi-admin' ) );

		$this->assertFalse( empty( $semi_admin_users ) );
	}
}
