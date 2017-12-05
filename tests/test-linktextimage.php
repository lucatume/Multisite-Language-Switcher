<?php
/**
 * Tests for LinkTextImage
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\LinkTextImage;

/**
 * WP_Test_MslsLinkTextImage
 */
class WP_Test_MslsLinkTextImage extends Msls_UnitTestCase {

	/**
	 * Verify the static get_description-method
	 */
	function test_get_description_method() {
		$this->assertInternalType( 'string', LinkTextImage::get_description() );
	}

}
