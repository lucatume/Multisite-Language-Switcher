<?php
/**
 * PostType
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

namespace realloc\Msls;

/**
 * Content types: Post types (Pages, Posts, ...)
 * @package Msls
 */
class PostType extends ContentTypes implements RegistryInstance {

	/**
	 * Constructor
	 * @uses get_post_types
	 */
	public function __construct() {
		$this->types = array_merge(
			array( 'post', 'page' ), // we don't need attachment, revision or nav_menu_item here
			get_post_types(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'names',
				'and'
			)
		);

		$_request = MslsPlugin::get_superglobals( array( 'post_type' ) );
		if ( '' != $_request['post_type'] ) {
			$this->request = esc_attr( $_request['post_type'] );
		}
		else {
			$this->request = get_post_type();
			if ( ! $this->request ) {
				$this->request = 'post';
			}
		}
	}

	/**
	 * Check for post_type
	 * @return bool
	 */
	function is_post_type() {
		return true;
	}

	/**
	 * Get or create an instance of PostType
	 * @todo Until PHP 5.2 is not longer the minimum for WordPress ...
	 * @return MslsBlogPostType
	 */
	public static function instance() {
		if ( ! ( $obj = MslsRegistry::get_object( 'MslsBlogPostType' ) ) ) {
			$obj = new self();
			MslsRegistry::set_object( 'MslsBlogPostType', $obj );
		}
		return $obj;
	}

}
