<?php
/**
 * Tests for Widget
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Widget;

/**
 * WP_Test_MslsWidget
 */
class WP_Test_MslsWidget extends Msls_UnitTestCase {

	public function get_test() {
		\Mockery::mock( '\WP_Widget' );

		\WP_Mock::passthruFunction( 'wp_parse_args' );

		$obj = Widget::init();
		$obj->id_base = 'abc';
		
		return $obj;
	}

	function test_widget_method() {
		$this->expectOutputString( 'No available translations found' );

		$this->get_test()->widget( [], [] );
	}

	function test_update_method() {
		$obj = $this->get_test();

		$result = $obj->update( [], [] );
		$this->assertInternalType( 'array', $result );
		$this->assertEquals( [], $result );

		$result = $obj->update( [ 'title' => 'abc' ], [] );
		$this->assertInternalType( 'array', $result );
		$this->assertEquals( [ 'title' => 'abc' ], $result );
		
		$result = $obj->update( [ 'title' => 'xyz' ], [ 'title' => 'abc' ] );
		$this->assertInternalType( 'array', $result );
		$this->assertEquals( [ 'title' => 'xyz' ], $result );
	}

}
