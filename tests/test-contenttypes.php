<?php
/**
 * Tests for ContentTypes
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\ContentTypes;

/**
 * Class WP_Test_MslsContentTypes
 */
class WP_Test_MslsContentTypes extends Msls_UnitTestCase {

	function test_is_post_type() {
		$this->assertFalse( ( new ContentTypes() )->is_post_type() );
	}

	function test_is_taxonomy() {
		$this->assertFalse( ( new ContentTypes() )->is_taxonomy() );
	}

	function test_acl_request() {
		$this->assertInternalType( 'string', ( new ContentTypes() )->acl_request() );
	}

	function test_get() {
		$this->assertInternalType( 'array', ( new ContentTypes() )->get() );
	}

	function test_get_request() {
		$this->assertInternalType( 'string', ( new ContentTypes() )->get_request() );
	}

}
