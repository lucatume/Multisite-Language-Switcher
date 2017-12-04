<?php
/**
 * CustomColumnTaxonomy
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @since 2.0
 */

namespace realloc\Msls;

/**
 * Handling of existing/not existing translations in the backend
 * listings of various taxonomies
 * @package Msls
 */
class CustomColumnTaxonomy extends CustomColumn {

	/**
	 * Init hooks
	 *
	 * @codeCoverageIgnore
	 *
	 * @return CustomColumnTaxonomy
	 */
	public function init_hooks() {
		if ( ! $this->options->is_excluded() ) {
			$taxonomy = MslsTaxonomy::instance()->get_request();

			if ( ! empty( $taxonomy ) ) {
				add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'th' ) );
				add_action( "manage_{$taxonomy}_custom_column", array( $this, 'column_default' ), 10, 3 );
				add_action( "delete_{$taxonomy}", array( $this, 'delete' ) );
			}
		}

		return $this;
	}

	/**
	 * Table body
	 *
	 * @param string $deprecated
	 * @param string $column_name
	 * @param int $item_id
	 *
	 * @return string
	 */
	public function column_default( $deprecated, $column_name, $item_id, $echo = true ) {
		return $this->td( $column_name, $item_id, $echo );
	}

	/**
	 * Delete
	 *
	 * @codeCoverageIgnore
	 *
	 * @param int $object_id
	 */
	public function delete( $object_id ) {
		$this->save( $object_id, 'MslsOptionsTax' );
	}

}
