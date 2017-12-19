<?php
/**
 * Tests for LanguageArray
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\LanguageArray;

/**
 * WP_Test_MslsLanguageArray
 */
class WP_Test_MslsLanguageArray extends Msls_UnitTestCase {

	public function get_test() {
		$arr = [
			'fr_FR' => 0, // not ok, value 0 is not ok as blog_id
			'it'    => 1,
			'de_DE' => 2,
			'x'     => 3, // not ok, minlength of string is 2
		];

		return new LanguageArray( $arr );
	}

	/**
	 * @covers LanguageArray::__construct
	 * @covers LanguageArray::get_val
	 */
	public function test_get_val_method() {
		$obj = $this->get_test();

		$this->assertEquals( 1, $obj->get_val( 'it' ) );
		$this->assertEquals( 0, $obj->get_val( 'fr_FR' ) );
	}

	public function test_get_arr_method() {
		$obj = $this->get_test();

		$this->assertEquals( [ 'it' => 1, 'de_DE' => 2 ], $obj->get_arr() );
		$this->assertEquals( [ 'it' => 1 ], $obj->get_arr( 'de_DE' ) );
	}

	public function test_set_method() {
		$obj = $this->get_test();

		$this->assertEquals( [ 'it' => 1, 'de_DE' => 2 ], $obj->get_arr() );
		$obj->set( 'fr_FR', 3 );
		$this->assertEquals( [ 'it' => 1, 'de_DE' => 2, 'fr_FR' => 3 ], $obj->get_arr() );
	}

}
