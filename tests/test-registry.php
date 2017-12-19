<?php
/**
 * Tests for Registry
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Registry;

/**
 * WP_Test_MslsRegistry
 */
class WP_Test_MslsRegistry extends Msls_UnitTestCase {

	/**
	 * Verify the instance-method
	 * @covers Registry::instance
	 */
	function get_test() {
		$obj = Registry::instance();

		$this->assertInstanceOf( 'realloc\Msls\Registry', $obj );

		return $obj;
	}

	/**
	 * Verify the set_object- and get_object-method
	 *
	 * @covers Registry::get_object
	 * @covers Registry::get
	 * @covers Registry::set_object
	 * @covers Registry::set
	 */
	function test_set_method() {
		$obj = $this->get_test();

		$this->assertEquals( null, $obj->get_object( 'test_var' ) );

		$obj->set_object( 'test_var', 1 );
		$this->assertEquals( 1, $obj->get_object( 'test_var' ) );

		$obj->set_object( 'test_var', null );
		$this->assertEquals( null, $obj->get_object( 'test_var' ) );
	}

}
