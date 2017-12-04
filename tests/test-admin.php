<?php
/**
 * Tests for Admin
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @package Msls
 */

use realloc\Msls\Admin;

/**
 * WP_Test_MslsAdmin
 */
class WP_Test_MslsAdmin extends Msls_UnitTestCase {

	function get_test( array $retval = [] ) {
		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		return new Admin( $options, $collection );
	}

	/**
	 * Verify the has_problems-method
	 */
	function test_has_problems() {
		$options = \Mockery::mock( 'realloc\Msls\Options' );
		$options->shouldReceive( 'get_available_languages' )->once()->andReturn( array( 'x' ) );

		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		$obj = new Admin( $options, $collection );

		$this->expectOutputRegex( '/^<div id="msls-warning" class="updated fade"><p>.*$/' );
		$retval = $obj->has_problems();

		$this->assertInternalType( 'bool', $retval );
	}

	/**
	 * Verify the subsubsub-method
	 */
	function test_subsubsub_no_plugin_active_blogs() {
		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );
		$collection->shouldReceive( 'get_plugin_active_blogs' )->once()->andReturn( [] );

		$obj = new Admin( $options, $collection );

		$this->assertEquals( '', $obj->subsubsub() );
	}


	/**
	 * Verify the blog_language-method
	 */
	function test_blog_language() {
		\WP_Mock::userFunction( 'selected', [ 'returns' => '' ] );

		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$options->shouldReceive( 'get_available_languages' )->once()->andReturn( [ 'de_DE', 'it_IT' ] );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		$obj = new Admin( $options, $collection );

		$this->expectOutputRegex( '/^<select id="blog_language" name="msls\[blog_language\]">.*$/' );
		$obj->blog_language();
	}

	/**
	 * Verify the admin_language-method
	 */
	function test_admin_language() {
		\WP_Mock::userFunction( 'selected', [ 'returns' => '' ] );

		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$options->shouldReceive( 'get_available_languages' )->once()->andReturn( [ 'de_DE', 'it_IT' ] );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );

		$obj = new Admin( $options, $collection );

		$this->expectOutputRegex( '/^<select id="admin_language" name="msls\[admin_language\]">.*$/' );
		$obj->admin_language();
	}

	/**
	 * Verify the reference_user-method
	 */
	function test_reference_user() {
		$options    = \Mockery::mock( 'realloc\Msls\Options' );
		$collection = \Mockery::mock( 'realloc\Msls\MslsBlogCollection' );
		$collection->shouldReceive( 'get_users' )->once()->andReturn( [] );

		$obj = new Admin( $options, $collection );

		$this->expectOutputRegex( '/^<select id="reference_user" name="msls\[reference_user\]">.*$/' );
		$obj->reference_user();
	}

	/**
	 * Verify the activate_autocomplete-method
	 */
	function test_activate_autocomplete() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="activate_autocomplete" name="msls[activate_autocomplete]" value="1" />' );
		$obj->activate_autocomplete();
	}

	/**
	 * Verify the sort_by_description-method
	 */
	function test_sort_by_description() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="sort_by_description" name="msls[sort_by_description]" value="1" />' );
		$obj->sort_by_description();
	}

	/**
	 * Verify the exclude_current_blog-method
	 */
	function test_exclude_current_blog() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="exclude_current_blog" name="msls[exclude_current_blog]" value="1" />' );
		$obj->exclude_current_blog();
	}

	/**
	 * Verify the only_with_translation-method
	 */
	function test_only_with_translation() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="only_with_translation" name="msls[only_with_translation]" value="1" />' );
		$obj->only_with_translation();
	}

	/**
	 * Verify the output_current_blog-method
	 */
	function test_output_current_blog() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="output_current_blog" name="msls[output_current_blog]" value="1" />' );
		$obj->output_current_blog();
	}

	/**
	 * Verify the description-method
	 */
	function test_description() {
		$obj = $this->get_test();

		$this->expectOutputString( '<input id="description" name="msls[description]" value="" size="40"/>' );
		$obj->description();
	}

	/**
	 * Verify the before_output-method
	 */
	function test_magic_text_call() {
		$obj = $this->get_test();

		$this->expectOutputString( '<input id="before_output" name="msls[before_output]" value="" size="30"/>' );
		$obj->text_before_output();
	}

	/**
	 * Verify the content_filter-method
	 */
	function test_content_filter() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->expectOutputString( '<input type="checkbox" id="content_filter" name="msls[content_filter]" value="1" />' );
		$obj->content_filter();
	}

	/**
	 * Verify the content_priority-method
	 */
	function test_content_priority() {
		$obj = $this->get_test();

		$this->expectOutputRegex( '/^<select id="content_priority" name="msls\[content_priority\]">.*$/' );
		$obj->content_priority();
	}

	/**
	 * Verify the render_checkbox-method
	 */
	function test_render_checkbox() {
		\WP_Mock::userFunction( 'checked', [ 'returns' => '' ] );

		$obj = $this->get_test();

		$this->assertInternalType( 'string', $obj->render_checkbox( 'test' ) );
	}

	/**
	 * Verify the render_input-method
	 */
	function test_render_input() {
		$obj = $this->get_test();

		$this->assertInternalType( 'string', $obj->render_input( 'test' ) );
	}

	/**
	 * Verify the render_select-method
	 */
	function test_render_select() {
		$obj = $this->get_test();

		$arr = array( 'a', 'b', 'c' );
		$this->assertInternalType( 'string', $obj->render_select( 'test', $arr ) );
	}

	/**
	 * Verify the validate-method
	 */
	function test_validate() {
		$obj = $this->get_test();

		$arr = array();
		$this->assertEquals( array( 'display' => 0 ), $obj->validate( $arr ) );
		$arr = array( 'image_url' => '/test/', 'display' => '1' );
		$this->assertEquals( array( 'image_url' => '/test', 'display' => 1 ), $obj->validate( $arr ) );
	}

	/**
	 * Verify the set_blog_language-method
	 */
	function test_set_blog_language() {
		\WP_Mock::passthruFunction( 'update_option' );

		$obj = $this->get_test();

		$arr = array( 'abc' => true, 'blog_language' => 'it_IT' );
		$this->assertEquals( array( 'abc' => true ), $obj->set_blog_language( $arr ) );
	}

}
