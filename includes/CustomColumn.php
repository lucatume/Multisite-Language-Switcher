<?php
/**
 * CustomColumn
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @since 2.0
 */

namespace realloc\Msls;

/**
 * Handling of existing/not existing translations in the backend listings of
 * various post types
 *
 * @package Msls
 */
class CustomColumn extends MslsMain {

	/**
	 * Init hooks
	 *
	 * @codeCoverageIgnore
	 *
	 * @return CustomColumn
	 */
	public function init_hooks() {
		if ( ! $this->options->is_excluded() ) {
			$post_type = PostType::instance()->get_request();

			if ( ! empty( $post_type ) ) {
				add_filter( "manage_{$post_type}_posts_columns", array( $this, 'th' ) );
				add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'td' ), 10, 2 );
				add_action( 'trashed_post', array( $this, 'delete' ) );
			}
		}

		return $this;
	}

	/**
	 * Table header
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function th( $columns ) {
		$blogs = $this->collection->get();

		if ( ! empty( $blogs ) ) {
			$arr = array();

			foreach ( $blogs as $blog ) {
				$language = $blog->get_language();
				$flag_url = $this->options->get_flag_url( $language );

				$icon = new AdminIcon( null );
				$icon->set_language( $language )->set_src( $flag_url );

				$arr[] = $icon->get_img();
			}

			$columns['mslscol'] = implode( '&nbsp;', $arr );
		}

		return $columns;
	}

	/**
	 * Table body
	 *
	 * @param string $column_name
	 * @param int $item_id
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function td( $column_name, $item_id, $echo = true ) {
		$retval = '';
		$blogs  = $this->collection->get();

		if ( 'mslscol' == $column_name && ! empty( $blogs ) ) {
			$mydata = Options::create( $item_id );

			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog->userblog_id );

				$language = $blog->get_language();

				$icon = AdminIcon::create();
				$icon->set_language( $language );

				if ( $mydata->has_value( $language ) ) {
					$flag_url = $this->options->get_url( 'images/link_edit.png' );
					$icon->set_href( $mydata->$language )->set_src( $flag_url );
				} else {
					$flag_url = $this->options->get_url( 'images/link_add.png' );
					$icon->set_src( $flag_url );
				}

				$retval .= $icon;

				restore_current_blog();
			}
		}

		if ( $echo ) {
			echo $retval;
		}

		return $retval;
	}

}
