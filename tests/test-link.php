<?php
/**
 * Tests for Link
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Link;

/**
 * WP_Test_Link
 */
class WP_Test_Link extends Msls_UnitTestCase {

	/**
	 * Verify the static get_types-method
	 */
	function test_get_types() {
		$this->assertInternalType( 'array', Link::get_types() );
	}

	/**
	 * Verify the static get_description-method
	 */
	function test_get_description() {
		$this->assertInternalType( 'string', Link::get_description() );
	}

	/**
	 * Verify the static get_types_description-method
	 */
	function test_get_types_description() {
		$this->assertInternalType( 'array', Link::get_types_description() );
	}

	/**
	 * Verify the static callback-method
	 */
	function test_callback() {
		$this->assertEquals( '{Test}', Link::callback( 'Test' ) );
	}

	/**
	 * Verify the __toString-method
	 */
	function test_execute_filter_method() {
		$this->assertInternalType( 'string', ( new Link() )->__toString() );
	}

}
