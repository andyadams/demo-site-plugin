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
		<form method="post" action="options.php">
			<?php settings_fields( 'demo_site_plugin_options' ); ?>
			<?php do_settings_sections( 'demo_site_plugin_options', 'demo_site_plugin_options' ); ?>
			<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
		<form method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
			<input type="hidden" name="action" value="demo_site_action" />
			<input name="Submit" type="submit" value="<?php esc_attr_e('Do action'); ?>" />
		</form>
	</div>
	<?php
}