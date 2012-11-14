<?php
/**
 * MyPlugin Tests
 */
class DemoPluginTest extends WP_UnitTestCase {
    public $plugin_slug = 'demo_site_plugin';

    public function setUp() {
        parent::setUp();
    }

    public function testAppendContent() {
        $this->assertEquals( 'a', 'a' );
    }

    /**
     * A contrived example using some WordPress functionality
     */
    public function testPostTitle() {
        // This will simulate running WordPress' main query.
        // See wordpress-tests/lib/testcase.php
        $this->go_to('http://example.org/?p=1');

        // Now that the main query has run, we can do tests that are more functional in nature
        global $wp_query;
        $post = $wp_query->get_queried_object();
        $this->assertEquals('Hello world!', $post->post_title );
    }
}