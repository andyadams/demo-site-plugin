<?php

class CloneTest extends OTB_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		global $wpdb;

		foreach ( DSP_DatabaseHandler::$all_tables as $table_name ) {
			$wpdb->query( "DROP TABLE wp_dummy_$table_name;" );
		}
	}

	public function testCloneTablesAreCreated() {
		global $wpdb;

		DSP_DatabaseHandler::save_tables();
		DSP_DatabaseHandler::clone_defaults( 'wp_dummy_' );

		$result = $wpdb->query( "SHOW TABLES LIKE '%wp_dummy_%';" );

		$this->assertEquals( count( DSP_DatabaseHandler::$all_tables ), $result );
	}
}
