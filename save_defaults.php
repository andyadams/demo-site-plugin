<?php

add_action( 'admin_action_demo_site_plugin_set_default_database', 'demo_site_plugin_set_default_database' );
function demo_site_plugin_set_default_database() {
	if ( ! isset( $_POST['_wpnonce_demo_site_plugin_set_default_database'] ) || ! wp_verify_nonce( $_POST['_wpnonce_demo_site_plugin_set_default_database'], 'demo_site_plugin_set_default_database' ) ) {
		echo "Invalid request";
		exit;
	}

	$exported_tables = array(
		'commentmeta', 'comments', 'links', 'options', 'postmeta',
		'posts', 'terms', 'term_relationships', 'term_taxonomy', 'usermeta', 'users'
	);

	$success = true;

	foreach ( $exported_tables as $table_name ) {
		if ( ! demo_site_plugin_save_table_defaults( $table_name ) ) {
			$success = false;
			break;
		}
	}

	if ( $success ) {
		$redirect_url = add_query_arg( 'export_success', 'true', $_SERVER['HTTP_REFERER'] );
	} else {
		$redirect_url = add_query_arg( 'export_success', 'false', $_SERVER['HTTP_REFERER'] );
	}

	wp_redirect( $redirect_url );
	exit();
}

function demo_site_plugin_save_table_defaults( $table_name ) {
	global $wpdb;
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}{$table_name};" );

	if ( false === $results ) {
		return false;
	}

	$results = serialize( $results );

	update_option( "demo_site_plugin_{$table_name}_table_defaults", $results );

	return true;
}

function demo_site_plugin_reset_defaults() {
	$exported_tables = array(
		'commentmeta', 'comments', 'links', 'options', 'postmeta',
		'posts', 'terms', 'term_relationships', 'term_taxonomy', 'usermeta', 'users'
	);

	$success = true;

	foreach ( $exported_tables as $table_name ) {
		if ( ! demo_site_plugin_restore_table_to_defaults( $table_name ) ) {
			$success = false;
			break;
		}
	}
}

function demo_site_plugin_restore_table_to_defaults( $table_name ) {
	global $wpdb;

	$rows = unserialize( get_option( "demo_site_plugin_{$table_name}_table_defaults" ) );

	if ( $rows ) {
		foreach ( $rows as $row ) {
			$data = (array) $row;
			demo_site_plugin_unset_primary_key_for_table( $data, $table_name );
			$wpdb->insert(
				$wpdb->prefix . $table_name,
				$data
			);
		}
	}

	return true;
}

function demo_site_plugin_unset_primary_key_for_table( $data, $table_name ) {
	$primary_keys = array(
		'commentmeta' => 'meta_id',
		'comments' => 'comment_ID',
		'links' => 'link_id',
		'options' => 'option_id',
		'postmeta' => 'meta_id',
		'posts' => 'ID',
		'terms' => 'term_id',
		'term_taxonomy' => 'term_taxonomy_id',
		'usermeta' => 'umeta_id',
		'users' => 'ID'
	);

	if ( isset( $primary_keys[$table_name] ) ) {
		unset( $data[$primary_keys[$table_name]] );
	}
}
// Remove autosaves?
// Serialize the table
// Store as an option