<?php
/**
 * Tests for GetSet
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

/**
 * WP_Test_MslsGetSet
 */
class WP_Test_MslsGetSet extends Msls_UnitTestCase {

	/**
	 * Verify the is_empty-method
	 * @covers GetSet::is_empty
	 * @covers GetSet::__set
	 * @covers GetSet::__get
	 * @covers GetSet::__isset
	 * @covers GetSet::__unset

	 */
	function test_is_empty_method() {
		$obj = new MslsGetSet();

		$this->assertTrue( $obj->is_empty() );
		$obj->abc = 'test';

		$this->assertEquals( 'test' , $obj->abc );
		$this->assertTrue( isset( $obj->abc ) );
		$this->assertFalse( $obj->is_empty() );

		unset( $obj->abc );
		$this->assertTrue( $obj->is_empty() );

		$obj->temp = 'test';
		return $obj;
	}

	/**
	 * Verify the has_value-method
	 * @depends test_is_empty_method
	 */
	function test_has_value_method( $obj ) {
		$this->assertTrue( $obj->has_value( 'temp' ) );
	}
	
	/**
	 * Verify the get_arr-method
	 * @depends test_is_empty_method
	 */
	function test_get_arr_method( $obj ) {
		$this->assertEquals( array( 'temp' => 'test' ), $obj->get_arr() );
	}

	/**
	 * Verify the reset-method
	 * @depends test_is_empty_method
	 */
	function test_reset_method( $obj ) {
		$obj->reset();
		$this->assertEquals( array(), $obj->get_arr() );
	}

}
