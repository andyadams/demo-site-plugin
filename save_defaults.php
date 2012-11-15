<?php

add_action( 'admin_action_demo_site_action', 'demo_site_action' );
function demo_site_action() {

	$result = unserialize( $result );
	var_dump( $result ); exit;

	wp_redirect( $_SERVER['HTTP_REFERER'] );
	exit();
}

function demo_site_plugin_export_posts() {
	global $wpdb;
	$result = $wpdb->get_results( "SELECT * FROM wp_posts;" );
	$result = serialize( $result );

	add_option( 'demo_site_plugin_posts_table_default', $result );
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