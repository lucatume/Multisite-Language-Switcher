<?php
/**
 * Tests for MslsCustomColumnTaxonomy
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\CustomColumnTaxonomy;

/**
 * WP_Test_MslsCustomColumnTaxonomy
 */
class WP_Test_MslsCustomColumnTaxonomy extends Msls_UnitTestCase {

	function get_test( array $retval = [] ) {
		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		$collection->shouldReceive( 'get' )->once()->andReturn( $retval );

		return new CustomColumnTaxonomy( $options, $collection );
	}

	function test_column_default() {
		$obj = $this->get_test();

		$this->assertInternalType( 'string', $obj->column_default( null, [], 1, false ) );
	}

}
