<?php
/**
 * Tests for AdminIconTaxonomy
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\AdminIconTaxonomy;

/**
 * WP_Test_MslsAdminIconTaxonomy
 */
class WP_Test_MslsAdminIconTaxonomy extends Msls_UnitTestCase {

	function test_set_path() {
		WP_Mock::userFunction( 'add_query_arg', [ 'returns' => 'test', 'times' => 2 ] );

		$obj = new AdminIconTaxonomy( 'post_tag' );

		$this->assertInstanceOf( 'realloc\Msls\AdminIconTaxonomy', $obj->set_path() );
	}

	function test_set_language() {
		WP_Mock::userFunction( 'add_query_arg', [ 'returns' => 'test', 'times' => 1 ] );

		$obj = new AdminIconTaxonomy( 'post_tag' );

		$this->assertInstanceOf( 'realloc\Msls\AdminIconTaxonomy', $obj->set_language( 'de_DE' ) );
	}

	function test_set_src() {
		WP_Mock::userFunction( 'add_query_arg', [ 'returns' => 'test', 'times' => 1 ] );

		$obj = new AdminIconTaxonomy( 'post_tag' );

		$this->assertInstanceOf( 'realloc\Msls\AdminIconTaxonomy', $obj->set_src( '/dev/german_flag.png' ) );
	}

	function test_get_img() {
		WP_Mock::userFunction( 'add_query_arg', [ 'returns' => 'test', 'times' => 1 ] );

		$obj = new AdminIconTaxonomy( 'post_tag' );

		$this->assertEquals( '<img alt="de_DE" src="/dev/german_flag.png" />', $obj->get_img() );
	}

}
