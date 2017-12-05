<?php
/**
 * Tests for Blog
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Blog;

/**
 * WP_Test_MslsBlog
 */
class WP_Test_MslsBlog extends Msls_UnitTestCase {

	function get_test() {
		\WP_Mock::userFunction( 'get_blog_option', [ 'returns' => 'de_DE' ] );

		$blog              = new stdClass();
		$blog->userblog_id = 1;

		return new Blog( $blog, 'Test' );
	}

	/**
	 * Verify the __get-method
	 */
	function test___get() {
		$obj = $this->get_test();

		$this->assertEquals( 1, $obj->userblog_id );
	}

	/**
	 * Verify the get_description-method
	 */
	function test_get_description() {
		$obj = $this->get_test();

		$this->assertEquals( 'Test', $obj->get_description() );
	}

	/**
	 * Verify the get_language-method
	 */
	function test_get_language() {
		$obj = $this->get_test();

		$this->assertEquals( 'us', $obj->get_language() );
	}

	/**
	 * Verify the get_alpha2-method
	 */
	function test_get_alpha2() {
		$obj = $this->get_test();

		$this->assertEquals( 'en', $obj->get_alpha2() );
	}

	/**
	 * Dataprovider
	 *
	 * @return array
	 */
	public function compareProvider() {
		return array(
			array( 0, 0, 0 ),
			array( 0, 1, - 1 ),
			array( 1, 0, 1 ),
			array( - 1, - 2, 1 ),
			array( - 2, - 1, - 1 )
		);
	}

	/**
	 * Verify the _cmp-method
	 * @dataProvider compareProvider
	 */
	function test__cmp( $a, $b, $expected ) {
		$this->assertEquals( $expected, Blog::_cmp( $a, $b ) );

		$obj = new Blog( null, null );
		$this->assertEquals( $expected, $obj->_cmp( $a, $b ) );
	}

	/**
	 * Verify the language-method
	 */
	function test_language_cmp() {
		$a = new Blog( null, null );
		$b = new Blog( null, null );

		$this->assertEquals( 0, $a->language( $a, $b ) );
	}

	/**
	 * Verify the description-method
	 */
	function test_description_cmp() {
		$a = new Blog( null, null );
		$b = new Blog( null, null );

		$this->assertEquals( 0, $a->description( $a, $b ) );
	}

}
