<?php
/**
 * Plugin
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

namespace realloc\Msls;

/**
 * Provides functionalities for general hooks and activation/deactivation
 *
 * @package Msls
 */
class Plugin {

	/**
	 * @var Options
	 */
	protected $options;

	public function __construct( Options $options ) {
		$this->options = $options;
	}

	public static function init() {
		$options = Options::instance();
		$obj     = new self( $options );

		add_action( 'plugins_loaded', [ $obj, 'load_textdomain' ] );

		if ( function_exists( 'register_uninstall_hook' ) ) {
			register_uninstall_hook( MSLS_PLUGIN__FILE__, [ $obj, 'uninstall' ] );
		}

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'widgets_init', [ $obj, 'register_widget' ] );

			if ( is_admin() ) {
				wp_enqueue_style(
					'msls-styles',
					plugins_url( 'css/msls.css', MSLS_PLUGIN__FILE__ ),
					[],
					MSLS_PLUGIN_VERSION
				);

				if ( $options->activate_autocomplete ) {
					wp_enqueue_script(
						'msls-autocomplete',
						plugins_url( 'js/msls.min.js', MSLS_PLUGIN__FILE__ ),
						[ 'jquery-ui-autocomplete' ],
						MSLS_PLUGIN_VERSION
					);
				}
			}
		}

		return $obj;
	}

	/**
	 * Register widget
	 *
	 * The widget will only be registered if the current blog is not
	 * excluded in the configuration of the plugin.
	 *
	 * @return boolean
	 */
	public function register_widget() {
		if ( $this->options->is_excluded() ) {
			return false;
		}

		register_widget( Widget::init() );

		return true;
	}

	/**
	 * Load textdomain
	 *
	 * @return boolean
	 */
	public function load_textdomain() {
		return load_plugin_textdomain(
			'multisite-language-switcher',
			false,
			dirname( MSLS_PLUGIN_PATH ) . '/languages/'
		);
	}

	/**
	 * Message handler
	 *
	 * Prints a message box to the screen.
	 *
	 * @param string $message
	 * @param string $css_class
	 *
	 * @return boolean
	 */
	public static function message_handler( $message, $css_class = 'error' ) {
		if ( empty( $message ) ) {
			return false;
		}

		printf(
			'<div id="msls-warning" class="%s"><p>%s</p></div>',
			$css_class,
			$message
		);

		return true;
	}

	/**
	 * Uninstall plugin
	 *
	 * The plugin data in all blogs of the current network will be
	 * deleted after the uninstall procedure.
	 * @return boolean
	 */
	public static function uninstall() {
		/**
		 * We want to be sure that the user has not deactivated the
		 * multisite because we need to use switch_to_blog and
		 * restore_current_blog
		 */
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$cache = SqlCacher::init( __CLASS__ )->set_params( __METHOD__ );

			$blogs = $cache->get_results(
				$cache->prepare(
					"SELECT blog_id FROM {$cache->blogs} WHERE blog_id != %d AND site_id = %d",
					$cache->blogid,
					$cache->siteid
				)
			);

			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog->blog_id );
				self::cleanup();
				restore_current_blog();
			}
		}

		return self::cleanup();
	}

	/**
	 * Cleanup the options
	 *
	 * Removes all values of the current blogs which are stored in the
	 * options-table and returns true if it was successful.
	 * @return boolean
	 */
	public static function cleanup() {
		if ( delete_option( 'msls' ) ) {
			$cache = SqlCacher::init( __CLASS__ )->set_params( __METHOD__ );
			$sql   = $cache->prepare(
				"DELETE FROM {$cache->options} WHERE option_name LIKE %s",
				'msls_%'
			);
			return (bool) $cache->query( $sql );
		}
		return false;
	}

	/**
	 * Get specific vars from $_POST and $_GET in a safe way
	 *
	 * @param array $list
	 *
	 * @return array
	 */
	public static function get_superglobals( array $list ) {
		$arr = [];

		foreach ( $list as $var ) {
			if ( filter_has_var( INPUT_POST, $var ) ) {
				$arr[ $var ] = filter_input( INPUT_POST, $var );
			}
			elseif ( filter_has_var( INPUT_GET, $var ) ) {
				$arr[ $var ] = filter_input( INPUT_GET, $var );
			} else {
				$arr[ $var ] = '';
			}
		}

		return $arr;
	}

}
