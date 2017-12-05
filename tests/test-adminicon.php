<?php
/**
 * Tests for AdminIcon
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\AdminIcon;

/**
 * WP_Test_MslsAdminIcon
 */
class WP_Test_MslsAdminIcon extends Msls_UnitTestCase {

	function test_create() {
		$obj = new AdminIcon( 'post' );

		$this->assertInstanceOf( 'realloc\Msls\AdminIcon', $obj->set_path() );
		$this->assertInstanceOf( 'realloc\Msls\AdminIcon', $obj->set_language( 'de_DE' ) );
		$this->assertInstanceOf( 'realloc\Msls\AdminIcon', $obj->set_src( '/dev/german_flag.png' ) );

		$this->assertEquals( '<img alt="de_DE" src="/dev/german_flag.png" />', $obj->get_img() );
	}

}
