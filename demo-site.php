<?php
/*
Plugin Name: Demo site plugin
Plugin URI: #
Description:
Author: Andy Adams
Version: 0.1
Author URI: #
*/

function demo_site_plugin_init() {
	register_setting( 'demo_site_plugin', 'demo_site_plugin_options' );
	add_settings_section( 'demo_site_plugin_main', 'Main Settings', 'demo_site_plugin_section_text', 'demo_site_plugin' );
	add_settings_field( 'demo_site_plugin_string', 'Plugin Text Input', 'demo_site_plugin_string_html', 'demo_site_plugin', 'demo_site_plugin_main' );
}
add_action( 'admin_init', 'demo_site_plugin_init' );

function demo_site_plugin_section_text() {
	?>
	<p>This is the description</p>
	<?php
}

function demo_site_plugin_string_html() {
	?>
	<h3>hey hey hey</h3>
	<?php
}

function demo_site_plugin_add_options_page() {
	$title = __( 'Demo Site', 'demo_site_plugin' );
	add_options_page( $title, $title, 'manage_options', __FILE__, 'demo_site_plugin_options_form' );
}
add_action( 'admin_menu', 'demo_site_plugin_add_options_page' );

function demo_site_plugin_options_form() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Demo site plugin options', 'demo_site_plugin' ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'demo_site_plugin' ); ?>
			<?php do_settings_sections( 'demo_site_plugin', 'demo_site_plugin_options' ); ?>
			<input type="submit" class="button-primary" name="set_defaults" value="<?php _e( 'Set defaults', 'demo_site_plugin' ); ?>">
		</form>
	</div>
	<?php
}