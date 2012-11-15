<?php

add_action( 'admin_action_demo_site_plugin_set_default_database', 'demo_site_plugin_set_default_database' );
function demo_site_plugin_set_default_database() {
	if ( ! isset( $_POST['_wpnonce_demo_site_plugin_set_default_database'] ) || ! wp_verify_nonce( $_POST['_wpnonce_demo_site_plugin_set_default_database'], 'demo_site_plugin_set_default_database' ) ) {
		echo "Invalid request";
		exit;
	}

	if ( demo_site_plugin_export_posts() ) {
		$redirect_url = add_query_arg( 'export_success', 'true', $_SERVER['HTTP_REFERER'] );
	} else {
		$redirect_url = add_query_arg( 'export_success', 'false', $_SERVER['HTTP_REFERER'] );
	}

	wp_redirect( $redirect_url );
	exit();
}

function demo_site_plugin_export_posts() {
	global $wpdb;
	$result = $wpdb->get_results( "SELECT * FROM wp_posts;" );
	$result = serialize( $result );

	update_option( 'demo_site_plugin_posts_table_default', $result );

	return true;
}

function demo_site_plugin_import_posts() {
	$posts = unserialize( get_option( 'demo_site_plugin_posts_table_default' ) );

	foreach ( $posts as $post ) {
		$post = (array) $post;
		unset( $post['ID'] );
		wp_insert_post( $post );
	}
}

// Remove autosaves?
// Serialize the table
// Store as an option