<?php
/**
 * Tests for CustomColumn
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\CustomColumn;

/**
 * WP_Test_CustomColumn
 */
class WP_Test_MslsCustomColumn extends Msls_UnitTestCase {

	function get_test( array $retval = [] ) {
		$options    = \Mockery::mock( 'realloc\Msls\MslsOptions' );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		$collection->shouldReceive( 'get' )->once()->andReturn( $retval );

		return new CustomColumn( $options, $collection );
	}

	function test_th() {
		$obj = $this->get_test();

		$this->assertInternalType( 'array', $obj->th( [] ) );
	}

	function test_td() {
		$obj = $this->get_test();

		$this->assertInternalType( 'string', $obj->td( [], 1, false ) );
	}

}
