<?php
/*
Plugin Name: Demo site plugin
Plugin URI: #
Description:
Author: Andy Adams
Version: 0.1
Author URI: #
*/
require_once( dirname( __FILE__ ) . '/save_defaults.php' );

function demo_site_plugin_add_rewrite_rules() {
	add_rewrite_rule( 'demo-login/?$', 'index.php?demo_login=true', 'top' );
	add_rewrite_rule( 'demo/([a-zA-Z0-9]*)/?', 'index.php?demo_site=$matches[1]', 'top' );
	global $wp_rewrite;
	$wp_rewrite->flush_rules(false);
}
add_action( 'init', 'demo_site_plugin_add_rewrite_rules' );

function demo_site_plugin_switch_to_demo_site( $wp_query ) {
	$token = $wp_query->get( 'demo_site' );
	if ( isset( $token ) ) {
		$active_tokens = get_option( 'demo_site_plugin_active_demo_tokens' );

		if ( in_array( $token, $active_tokens ) ) {
			echo "HERE!";exit;
			demo_site_plugin_switch_to_site_for_token( $token );
		}
	}
}
add_action( 'parse_query', 'demo_site_plugin_switch_to_demo_site' );

function demo_site_plugin_template_redirect() {
	global $wp_query;

	if ( $wp_query->get( 'demo_login' ) ) {
		include( plugin_dir_path( __FILE__ ) . "/login.php" );
		exit();
	}
}
add_filter( 'template_redirect', 'demo_site_plugin_template_redirect' );

function demo_site_plugin_query_vars( $query_vars ){
    $query_vars[] = 'demo_login';
    $query_vars[] = 'demo_site';
    return $query_vars;
}
add_filter( 'query_vars', 'demo_site_plugin_query_vars' );

function demo_site_plugin_init() {
	register_setting( 'demo_site_plugin_options', 'demo_site_plugin_options' );
	add_settings_section( 'demo_site_plugin_main', 'Main Settings', 'demo_site_plugin_section_text', 'demo_site_plugin_options' );
	add_settings_field( 'demo_site_plugin_string', 'Plugin Text Input', 'demo_site_plugin_string_html', 'demo_site_plugin_options', 'demo_site_plugin_main' );
}
add_action( 'admin_init', 'demo_site_plugin_init' );

function demo_site_plugin_section_text() {
	?>
	<p>This is the description</p>
	<?php
}

function demo_site_plugin_string_html() {
	$options = get_option( 'demo_site_plugin_options' );
	?>
	<input id='plugin_text_string' name='demo_site_plugin_options[demo_site_plugin_string]' size='40' type='text' value='<?php echo $options['demo_site_plugin_string']; ?>' />
	<?php
}

function demo_site_plugin_add_options_page() {
	$title = __( 'Demo Site', 'demo_site_plugin' );
	add_options_page( $title, $title, 'manage_options', 'demo_site_plugin', 'demo_site_plugin_options_form' );
}
add_action( 'admin_menu', 'demo_site_plugin_add_options_page' );

function demo_site_plugin_options_form() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Demo site plugin options', 'demo_site_plugin' ); ?></h2>
		<?php if ( isset( $_GET['export_success'] ) && $_GET['export_success'] ) : ?>
			<div id="export-success" class="updated">
				<p><strong><?php _e( 'Defaults successfully saved', 'demo_site_plugin' ); ?></strong></p>
			</div>
		<?php endif; ?>
		<!--<form method="post" action="options.php">
			<?php settings_fields( 'demo_site_plugin_options' ); ?>
			<?php do_settings_sections( 'demo_site_plugin_options', 'demo_site_plugin_options' ); ?>
			<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>-->
		<form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
			<?php wp_nonce_field( 'demo_site_plugin_save_defaults', '_wpnonce_demo_site_plugin_save_defaults' ); ?>
			<input type="hidden" name="action" value="demo_site_plugin_save_defaults" />
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save current database as default', 'demo_site_plugin' ); ?>" />
		</form>
		<form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
			<?php wp_nonce_field( 'demo_site_plugin_reset_defaults', '_wpnonce_demo_site_plugin_reset_defaults' ); ?>
			<input type="hidden" name="action" value="demo_site_plugin_reset_defaults" />
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Reset database to saved defaults', 'demo_site_plugin' ); ?>" />
		</form>
	</div>
	<?php
}

function demo_site_plugin_create_site_with_token( $token ) {
	DSP_DatabaseHandler::clone_defaults( "wp_{$token}_" );

	demo_site_plugin_create_semi_admin_for_token( $token );

	update_option( 'demo_site_plugin_active_demo_tokens', array( $token ) );

	demo_site_plugin_switch_to_site_for_token( $token );
}

function demo_site_plugin_switch_to_site_for_token( $token ) {
	global $demo_site_plugin_current_token;

	$demo_site_plugin_current_token = $token;

	global $wpdb;

	$wpdb->set_prefix( "wp_{$token}_" );
}

function demo_site_plugin_admin_url( $url, $path, $blog_id ) {
	global $demo_site_plugin_current_token;

	if ( isset( $demo_site_plugin_current_token ) ) {
		$url = get_site_url( $blog_id, "demo/{$demo_site_plugin_current_token}/wp-admin/", 'admin' );
	}

	return $url;
}
add_filter( 'admin_url', 'demo_site_plugin_admin_url', 10, 3 );

function demo_site_plugin_create_semi_admin_for_token( $token ) {
	global $wpdb;

	$original_prefix = $wpdb->prefix;

	$wpdb->set_prefix( "wp_{$token}_" );

	add_role( 'semi-admin', 'Semi-Admin' );
	$semi_admin_id = wp_create_user( 'semi_admin', 'password' );
	$semi_admin = new WP_User( $semi_admin_id );
	$semi_admin->set_role( 'semi-admin' );

	$wpdb->set_prefix( $original_prefix );
}