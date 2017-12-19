<?php
/**
 * Tests for BlogCollection
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\BlogCollection;

/**
 * WP_Test_MslsBlogCollection
 */
class WP_Test_MslsBlogCollection extends Msls_UnitTestCase {

	/**
	 * Verify the instance-method
	 */
	function get_test( $blog_id = 1 ) {
		$blogs = [
			(object)[ 'userblog_id' => 1, 'description' => 'abc' ],
			(object)[ 'userblog_id' => 2, 'description' => 'xyz' ],
		];
		\WP_Mock::userFunction( 'has_filter', [ 'returns' => false, 'times' => 1 ] );
		\WP_Mock::userFunction('get_current_blog_id', [ 'returns' => $blog_id, 'times' => 1 ] );
		\WP_Mock::userFunction( 'get_blogs_of_user', [ 'returns' => $blogs, 'times' => 1 ] );

		$options = \Mockery::mock( 'realloc\Msls\Options' );
		$options->shouldReceive( 'get_order' )->once()->andReturn( 'language' );
		$options->shouldReceive( 'is_excluded' )->once()->andReturn( false );
		$options->shouldReceive( 'has_value' )->once()->andReturn( true );

		return new BlogCollection( $options );
	}

	/**
	 * Verify the get_configured_blog_description-method
	 */
	function test_get_description() {
		$obj = $this->get_test();

		$this->assertEquals( 'Test', $obj->get_configured_blog_description( 0, 'Test' ) );
		$this->assertEquals( false, $obj->get_configured_blog_description( 0, false ) );
	}

	/**
	 * Verify the test_get_blogs_of_reference_user-method
	 */
	function test_get_blogs_of_reference_user() {
		$obj = $this->get_test();

		$options = $this->getMock( 'Options' );
		$this->assertInternalType( 'array', $obj->get_blogs_of_reference_user( $options ) );
	}

	/**
	 * Verify the get_current_blog_id-method
	 */
	function test_get_current_blog_id() {
		$obj = $this->get_test();

		$this->assertInternalType( 'integer', $obj->get_current_blog_id() );
	}

	/**
	 * Verify the has_current_blog-method
	 */
	function test_has_current_blog() {
		$obj = $this->get_test();

		$this->assertInternalType( 'boolean', $obj->has_current_blog() );
	}

	/**
	 * Verify the get_objects-method
	 */
	function test_get_objects() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->get_objects() );
	}

	/**
	 * Verify the is_plugin_active-method
	 */
	function test_is_plugin_active() {
		\WP_Mock::userFunction( 'get_site_option', [ 'returns' => [], 'times' => 1 ] );
		\WP_Mock::userFunction( 'get_blog_option', [ 'returns' => [ MSLS_PLUGIN_PATH ], 'times' => 1 ] );

		$obj = $this->get_test();

		$this->assertInternalType( 'boolean', $obj->is_plugin_active( 0 ) );
	}

	/**
	 * Verify the get_plugin_active_blogs-method
	 */
	function test_get_plugin_active_blogs() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->get_plugin_active_blogs() );
	}

	/**
	 * Verify the get-method
	 */
	function test_get() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->get() );
	}

	/**
	 * Verify the get_filtered-method
	 */
	function test_get_filtered() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->get_filtered() );
	}

	/**
	 * Verify the get_users-method
	 */
	function test_get_users() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->get_users() );
	}

}
