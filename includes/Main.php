<?php
/**
 * Main
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

namespace realloc\Msls;

/**
 * Abstraction for the hook classes
 * @package Msls
 */
abstract class Main {

	/**
	 * @var Options
	 * @var Blog[]
	 */
	protected
		$options,
		$collection;

	/**
	 * Init
	 *
	 * @return Main
	 */
	public static function init() {
		$options = Options::instance();
		$blogs   = BlogCollection::instance();
		$obj     = new static( $options, $blogs );

		$obj->init_hooks();

		return $obj;
	}

	/**
	 * Does soemthing and returns itself
	 *
	 * @return $this
	 */
	public function init_hooks() {
		return $this;
	}

	/**
	 * MslsCustomColumn constructor.
	 *
	 * @param Options $options
	 * @param BlogCollection $collection
	 */
	public function __construct( Options $options, BlogCollection $collection ) {
		$this->options    = $options;
		$this->collection = $collection;
	}

	/**
	 * Prints a message in the error log if WP_DEBUG is true
	 *
	 * @param mixed $message
	 */
	public function debugger( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				$message = print_r( $message, true );
			}

			error_log( 'MSLS Debug: ' . $message );
		}
	}

	/**
	 * Get the input array
	 *
	 * @param int $object_id
	 * @return array
	 */
	public function get_input_array( $object_id ) {
		$arr = array();

		$current_blog = $this->collection->get_current_blog();
		if ( ! is_null( $current_blog ) ) {
			$arr[ $current_blog->get_language() ] = (int) $object_id;
		}

		$input_post = filter_input_array( INPUT_POST );
		if ( is_array( $input_post ) ) {
			foreach ( $input_post as $key => $value ) {
				if ( false !== strpos( $key, 'msls_input_' ) && ! empty( $value ) ) {
					$arr[ substr( $key, 11 ) ] = (int) $value;
				}
			}
		}

		return $arr;
	}

	/**
	 * Checks if the current input comes from the autosave-functionality
	 * @param int $post_id
	 * @return bool
	 */
	public function is_autosave( $post_id ) {
		return( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id );
	}

	/**
	 * Checks for the nonce in the INPUT_POST
	 * @return boolean
	 */
	public function verify_nonce() {
		return(
			filter_has_var( INPUT_POST, 'msls_noncename' ) &&
			wp_verify_nonce( filter_input( INPUT_POST, 'msls_noncename' ), MSLS_PLUGIN_PATH )
		);
	}

	/**
	 * Delete
	 * @param int $object_id
	 * @codeCoverageIgnore
	 */
	public function delete( $object_id ) {
		$this->save( $object_id, 'OptionsPost' );
	}

	/**
	 * Save
	 *
	 * @codeCoverageIgnore
	 *
	 * @param int $object_id
	 * @param string $class
	 */
	protected function save( $object_id, $class ) {
		if ( has_action( 'msls_main_save' ) ) {
			/**
			 * Calls completely customized save-routine
			 * @since 0.9.9
			 * @param int $object_id
			 * @param string Classname
			 */
			do_action( 'msls_main_save', $object_id, $class );
			return;
		}

		if ( ! $this->collection->has_current_blog() ) {
			$this->debugger( '$this->collection->has_current_blog() returns false.' );
			return;
		}

		$language = $this->collection->get_current_blog()->get_language();
		$msla     = new LanguageArray( $this->get_input_array( $object_id ) );
		$options  = new $class( $object_id );
		$temp     = $options->get_arr();

		if ( 0 != $msla->get_val( $language ) ) {
			$options->save( $msla->get_arr( $language ) );
		}
		else {
			$options->delete();
		}

		foreach ( $this->collection->get() as $blog ) {
			switch_to_blog( $blog->userblog_id );

			$language = $blog->get_language();
			$larr_id  = $msla->get_val( $language );

			if ( 0 != $larr_id ) {
				$options = new $class( $larr_id );
				$options->save( $msla->get_arr( $language ) );
			}
			elseif ( isset( $temp[ $language ] ) ) {
				$options = new $class( $temp[ $language ] );
				$options->delete();
			}

			restore_current_blog();
		}
	}

}
