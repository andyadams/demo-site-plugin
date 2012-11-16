<?php

class DSP_DatabaseHandler {
	public static $all_tables = array(
		'commentmeta', 'comments', 'links', 'options', 'postmeta',
		'posts', 'terms', 'term_relationships', 'term_taxonomy', 'usermeta', 'users'
	);

	public function save_defaults() {
		self::check_nonce_for_action( 'demo_site_plugin_save_defaults' );

		$success = self::save_tables();

		$redirect_url = add_query_arg( 'export_success', $success, $_SERVER['HTTP_REFERER'] );

		wp_redirect( $redirect_url );
		exit();
	}

	public function reset_defaults() {
		self::check_nonce_for_action( 'demo_site_plugin_reset_defaults' );

		$success = self::reset_tables();

		$redirect_url = add_query_arg( 'reset_success', $success, $_SERVER['HTTP_REFERER'] );

		wp_redirect( $redirect_url );
		exit();
	}

	public function clone_defaults( $prefix, $tables=NULL ) {
		self::reset_tables( $prefix, $tables );
	}

	protected function check_nonce_for_action( $action ) {
		if ( ! isset( $_POST["_wpnonce_$action"] ) || ! wp_verify_nonce( $_POST["_wpnonce_$action"], $action ) ) {
			echo "Invalid request";
			exit;
		}
	}

	public function save_tables( $prefix=NULL, $tables=NULL ) {
		global $wpdb;

		if ( NULL === $prefix ) {
			$prefix = $wpdb->prefix;
		}

		if ( NULL === $tables ) {
			$tables = self::$all_tables;
		}

		foreach ( $tables as $table_name ) {
			$results = $wpdb->get_results( "SELECT * FROM {$prefix}{$table_name};" );

			if ( false === $results ) {
				return false;
			}

			$results = serialize( $results );

			update_option( "demo_site_plugin_{$table_name}_table_defaults", $results );
		}

		return true;
	}

	public function reset_tables( $prefix=NULL, $tables=NULL ) {
		global $wpdb;

		if ( NULL === $prefix ) {
			$prefix = $wpdb->prefix;
		}

		if ( NULL === $tables ) {
			$tables = self::$all_tables;
		}

		$original_prefix = $wpdb->prefix;
		$wpdb->set_prefix( $prefix );

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( 'all' );

		$wpdb->set_prefix( $original_prefix );

		foreach ( $tables as $table_name ) {
			$rows = maybe_unserialize( get_option( "demo_site_plugin_{$table_name}_table_defaults" ) );

			$wpdb->query( "DELETE FROM {$prefix}{$table_name};" );

			if ( $rows ) {
				foreach ( $rows as $row ) {
					$data = (array) $row;
					$result = $wpdb->insert(
						$prefix . $table_name,
						$data
					);

					if ( false === $result ) {
						return false;
					}
				}
			}
		}

		return true;
	}
}

add_action( 'admin_action_demo_site_plugin_save_defaults', array( 'DSP_DatabaseHandler', 'save_defaults' ) );
add_action( 'admin_action_demo_site_plugin_reset_defaults', array( 'DSP_DatabaseHandler', 'reset_defaults' ) );