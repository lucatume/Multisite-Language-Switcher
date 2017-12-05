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

	function test_create() {
		$obj = new AdminIconTaxonomy( 'post_tag' );

		$this->assertInstanceOf( 'AdminIconTaxonomy', $obj->set_path() );
		$this->assertInstanceOf( 'AdminIconTaxonomy', $obj->set_language( 'de_DE' ) );
		$this->assertInstanceOf( 'AdminIconTaxonomy', $obj->set_src( '/dev/german_flag.png' ) );

		$this->assertEquals( '<img alt="de_DE" src="/dev/german_flag.png" />', $obj->get_img() );
	}

}
