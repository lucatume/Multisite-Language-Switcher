<?php
/**
 * Tests for MetaBox
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\MetaBox;

/**
 * WP_Test_MslsMetaBox
 */
class WP_Test_MslsMetaBox extends Msls_UnitTestCase {

	function get_test() {
		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$collection = \Mockery::mock( 'realloc\Msls\BlogCollection' );

		$obj        = new MetaBox( $options, $collection );

		return $obj;
	}

}
