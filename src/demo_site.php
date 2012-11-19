<?php

class DSP_DemoSite {
	public $token, $prefix;

	public function __construct( $_token=NULL, $_prefix=NULL ) {
		if ( $_token ) {
			$this->token = $_token;
		} else {
			$this->token = $this->generate_token();
		}

		if ( $_prefix ) {
			$this->prefix = $_prefix;
		} else {
			$this->prefix = 'wp_' . $this->token . '_';
		}
	}

	public function activate() {
		global $demo_site_plugin_current_token;

		$demo_site_plugin_current_token = $this->token;

		global $wpdb;

		$wpdb->set_prefix( $this->prefix );
	}

	public function deactivate() {
		// Logic for returning to default installation
	}

	public function create() {
		// Logic for creating this site
		DSP_DatabaseHandler::clone_defaults( $this->prefix );

		$this->create_semi_admin();

		$active_tokens = get_option( 'demo_site_plugin_active_demo_tokens', array() );

		$active_tokens[] = $this->token;

		update_option( 'demo_site_plugin_active_demo_tokens', $active_tokens );
	}

	public function delete() {
		// Logic for deleting this site
	}

	protected function generate_token() {
		return 'abcdef';
	}

	protected function create_semi_admin() {
		global $wpdb;

		$original_prefix = $wpdb->prefix;

		$wpdb->set_prefix( $this->prefix );

		add_role( 'semi-admin', 'Semi-Admin' );
		$semi_admin_id = wp_create_user( 'semi_admin', 'password' );
		$semi_admin = new WP_User( $semi_admin_id );
		$semi_admin->set_role( 'semi-admin' );

		$wpdb->set_prefix( $original_prefix );
	}
}