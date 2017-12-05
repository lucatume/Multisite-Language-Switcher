<?php
/**
 * Tests for LinkImageOnly
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\LinkImageOnly;

/**
 * WP_Test_MslsLinkImageOnly
 */
class WP_Test_MslsLinkImageOnly extends Msls_UnitTestCase {

	/**
	 * Verify the static get_description-method
	 */
	function test_get_description_method() {
		$this->assertInternalType( 'string', LinkImageOnly::get_description() );
	}

}
