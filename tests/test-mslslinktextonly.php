<?php
/**
 * Tests for LinkTextOnly
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\LinkTextOnly;

/**
 * WP_Test_MslsLinkTextOnly
 */
class WP_Test_MslsLinkTextOnly extends Msls_UnitTestCase {

	/**
	 * Verify the static get_description-method
	 */
	function test_get_description_method() {
		$this->assertInternalType( 'string', LinkTextOnly::get_description() );
	}

}
