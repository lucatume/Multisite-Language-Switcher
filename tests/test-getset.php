<?php
/**
 * Tests for GetSet
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\GetSet;

/**
 * WP_Test_MslsGetSet
 */
class WP_Test_MslsGetSet extends Msls_UnitTestCase {

	function test_is_empty() {
		$obj = new GetSet();

		$this->assertTrue( $obj->is_empty() );

		$obj->abc = 'test';

		$this->assertEquals( 'test' , $obj->abc );
		$this->assertTrue( isset( $obj->abc ) );
		$this->assertFalse( $obj->is_empty() );

		unset( $obj->abc );

		$this->assertTrue( $obj->is_empty() );
	}

	/**
	 * Verify the has_value-method
	 */
	function test_has_value() {
		$obj = new GetSet();

		$obj->temp = 'test';

		$this->assertTrue( $obj->has_value( 'temp' ) );
	}
	
	function test_get_arr() {
		$obj = new GetSet();

		$obj->temp = 'test';

		$this->assertEquals( [ 'temp' => 'test' ], $obj->get_arr() );
	}

	function test_reset() {
		$obj = new GetSet();

		$obj->temp = 'test';
		$obj->reset();

		$this->assertEquals( [], $obj->get_arr() );
	}

	function test_set_empty() {
		$obj = new GetSet();

		$obj->temp = '';

		$this->assertEquals( [], $obj->get_arr() );
	}

}
