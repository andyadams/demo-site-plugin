<?php
// Load WordPress test environment
// https://github.com/nb/wordpress-tests
//
// The path to wordpress-tests
$path = dirname( __FILE__ ) . '/../vendor/wordpress-tests/bootstrap.php';

if( file_exists( $path ) ) {
	$GLOBALS['wp_tests_options'] = array(
		'active_plugins' => array( 'demo-site-plugin/demo_site_plugin.php' )
	);
    require_once $path;
} else {
    exit( "Couldn't find path to wordpress-tests/bootstrap.php\n" );
}

require_once( dirname( __FILE__ ) . '/OTB_UnitTestCase.php' );