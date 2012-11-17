<?php

class AdminUrlTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testAdminUrlForToken() {
		demo_site_plugin_switch_to_site_for_token( $this->token );

		$this->assertEquals( "http://example.org/demo/{$this->token}/wp-admin/", get_admin_url() );
	}

	public function testAdminUrlQueryVars() {
		global $wp_rewrite, $wp_query;

		// Test with pretty permalinks
		$wp_rewrite->set_permalink_structure( '/%postname%/' );

		$this->go_to( 'http://example.org/demo/abc123/' );

		global $wp_query;

		$this->assertEquals( $wp_query->get( 'demo_site' ), 'abc123' );
	}
}
