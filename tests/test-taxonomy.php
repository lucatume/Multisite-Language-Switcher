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

	function get_test() {
		\WP_Mock::userFunction( 'get_taxonomies', [ 'returns' => [ 'category', 'post_tag' ], 'times' => 1 ] );

		return new Taxonomy();
	}

	function test_is_taxonomy() {
		$this->assertTrue( $this->get_test()->is_taxonomy() );
	}

	function test_acl_request() {
		$this->assertInternalType( 'string', $this->get_test()->acl_request() );
	}
	
	function test_get_post_type() {
		$this->assertInternalType( 'string', $this->get_test()->get_post_type() );
	}

}
