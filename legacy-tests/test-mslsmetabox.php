<?php
/**
 * Tests for MetaBox
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

/**
 * WP_Test_MslsMetaBox
 */
class WP_Test_MslsMetaBox extends Msls_UnitTestCase {

	/**
	 * Verify the static init-method
	 */
	function test_init_method() {
		$obj = MslsMetaBox::init();
		$this->assertInstanceOf( 'MetaBox', $obj );
		return $obj;
	}

}
