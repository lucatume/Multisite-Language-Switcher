<?php
/**
 * Tests for Taxonomy
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Taxonomy;

/**
 * WP_Test_MslsTaxonomy
 */
class WP_Test_MslsTaxonomy extends Msls_UnitTestCase {

	function test_is_taxonomy() {
		$this->assertTrue( ( new Taxonomy() )->is_taxonomy() );
	}

	function test_acl_request() {
		$this->assertInternalType( 'string', ( new Taxonomy() )->acl_request() );
	}
	
	function test_get_post_type() {
		$this->assertInternalType( 'string', ( new Taxonomy() )->get_post_type() );
	}

}
