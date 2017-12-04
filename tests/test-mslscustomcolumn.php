<?php
/**
 * Tests for MslsCustomColumn
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

/**
 * WP_Test_MslsCustomColumn
 */
class WP_Test_MslsCustomColumn extends Msls_UnitTestCase {

	/**
	 * Verify the th-method
	 */
	function test_th_method() {
		$options    = \Mockery::mock( 'MslsOptions' );
		$collection = \Mockery::mock( 'MslsBlogCollection' );

		$collection->shouldReceive( 'get' )->once()->andReturn( [] );

		$obj = new MslsCustomColumn( $options, $collection );

		$this->assertInternalType( 'array', $obj->th( [] ) );
	}

}
