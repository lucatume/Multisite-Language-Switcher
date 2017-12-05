<?php
/**
 * AdminIconTaxonomy
 * @author Dennis Ploetner <re@lloc.de>
 * @since 2.0
 */

namespace realloc\Msls;

/**
 * Handles backend icons for taxonomies
 * @package Msls
 */
class AdminIconTaxonomy extends AdminIcon {

	/**
	 * Path
	 * @var string
	 */
	protected $path = 'edit-tags.php';

	/**
	 * Set href
	 *
	 * @uses get_edit_term_link() 
	 *
	 * @param int $id
	 *
	 * @return AdminIconTaxonomy
	 */
	public function set_href( $id ) {
		$this->href = get_edit_term_link( $id, $this->type, $this->object_type );

		return $this;
	}

	/**
	 * Set the path by type
	 *
	 * @uses add_query_arg()
	 * @return AdminIconTaxonomy
	 */
	public function set_path() {
		$args = array( 'taxonomy' => $this->type );

		if ( ! empty( $this->object_type ) ) {
			$args['post_type'] =  $this->object_type;
		}

		$this->path = add_query_arg( $args, $this->path );

		return $this;
	}

}
