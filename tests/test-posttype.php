<?php
/**
 * Tests for PostType
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\PostType;

/**
 * WP_Test_MslsPostType
 */
class WP_Test_MslsPostType extends Msls_UnitTestCase {

	function test_is_post_type() {
		$this->assertTrue( ( new PostType() )->is_post_type() );
	}

}
