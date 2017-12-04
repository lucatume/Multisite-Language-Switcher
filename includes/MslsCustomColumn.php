<?php
/**
 * MslsCustomColumn
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

/**
 * Handling of existing/not existing translations in the backend listings of
 * various post types
 *
 * @package Msls
 */
class MslsCustomColumn extends MslsMain {

	/**
	 * @var MslsOptions
	 * @var MslsBlog[]
	 */
	protected
		$options,
		$blogs;

	/**
	 * Factory
	 *
	 * @codeCoverageIgnore
	 *
	 * @return MslsCustomColumn
	 */
	public static function init() {
		$options = MslsOptions::instance();
		$blogs   = MslsBlogCollection::instance();
		$obj     = new self( $options, $blogs );

		if ( ! $options->is_excluded() ) {
			$post_type = MslsPostType::instance()->get_request();
			if ( ! empty( $post_type ) ) {
				add_filter( "manage_{$post_type}_posts_columns", array( $obj, 'th' ) );
				add_action( "manage_{$post_type}_posts_custom_column", array( $obj, 'td' ), 10, 2 );
				add_action( 'trashed_post', array( $obj, 'delete' ) );
			}
		}
		return $obj;
	}

	public function __construct( MslsOptions $options, MslsBlogCollection $collection ) {
		$this->options = $options;
		$this->blogs   = $collection->get();
	}

	/**
	 * Table header
	 * @param array $columns
	 * @return array
	 */
	public function th( $columns ) {
		if ( $this->blogs ) {
			$arr = array();
			foreach ( $this->blogs as $blog ) {
				$language = $blog->get_language();
				$flag_url = $this->options->get_flag_url( $language );

				$icon = new MslsAdminIcon( null );
				$icon->set_language( $language )->set_src( $flag_url );

				$arr[] = $icon->get_img();
			}
			$columns['mslscol'] = implode( '&nbsp;', $arr );
		}
		return $columns;
	}

	/**
	 * Table body
	 * @param string $column_name
	 * @param int $item_id
	 */
	public function td( $column_name, $item_id ) {
		if ( 'mslscol' == $column_name ) {
			if ( $this->blogs ) {
				$mydata = MslsOptions::create( $item_id );
				foreach ( $this->blogs as $blog ) {
					switch_to_blog( $blog->userblog_id );

					$language = $blog->get_language();

					$icon = MslsAdminIcon::create();
					$icon->set_language( $language );

					if ( $mydata->has_value( $language ) ) {
						$flag_url = $this->options->get_url( 'images/link_edit.png' );
						$icon->set_href( $mydata->$language )->set_src( $flag_url );
					}
					else {
						$flag_url = $this->options->get_url( 'images/link_add.png' );
						$icon->set_src( $flag_url );
					}

					echo $icon;

					restore_current_blog();
				}
			}
		}
	}

}
