<?php
/**
 * BlogCollection
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

namespace realloc\Msls;

/**
 * Collection of blog-objects
 *
 * @package Msls
 */
class BlogCollection implements RegistryInstance {

	/**
	 * ID of the current blog
	 * @var int
	 */
	private $current_blog_id;

	/**
	 * True if the current blog should be in the output
	 * @var bool
	 */
	private $current_blog_output;

	/**
	 * Collection of Blog-objects
	 * @var array
	 */
	private $objects = array();

	/**
	 * Active plugins in the whole network
	 * @var array
	 */
	private $active_plugins;

	/**
	 * @var Options
	 */
	protected $options;

	/**
	 * Constructor
	 */
	public function __construct( Options $options ) {
		if ( ! has_filter( 'msls_blog_collection_description' ) ) {
			add_filter( 'msls_blog_collection_description', array( $this, 'get_configured_blog_description' ), 10, 2 );
		}

		$this->options             = $options;
		$this->current_blog_id     = get_current_blog_id();
		$this->current_blog_output = isset( $this->options->output_current_blog );

		if ( ! $this->options->is_excluded() ) {
			/**
			 * Returns custom filtered blogs of the blogs_collection
			 * @since 0.9.8
			 *
			 * @param array $blogs_collection
			 */
			$blogs_collection = (array) apply_filters(
				'msls_blog_collection_construct',
				$this->get_blogs_of_reference_user()
			);

			foreach ( $blogs_collection as $blog ) {
				$description = false;

				if ( $blog->userblog_id == $this->current_blog_id ) {
					$description = $this->options->description;
				}
				elseif ( ! $this->is_plugin_active( $blog->userblog_id ) ) {
					continue;
				}

				$description = apply_filters(
					'msls_blog_collection_description',
					$blog->userblog_id,
					$description
				);

				if ( false !== $description ) {
					$this->objects[ $blog->userblog_id ] = new Blog(
						$blog,
						$description
					);
				}
			}

			$objects_order = $this->options->get_order();
			uasort( $this->objects, function ( $a, $b ) use ( $objects_order ) {
				return strcmp( $a->$objects_order, $b->$objects_order );
			} );
		}
	}

	/**
	 * Returns the description of an configured blog or false if it is not configured
	 *
	 * @param int $blog_id
	 * @param string|bool $description
	 *
	 * @return string|bool
	 */
	public static function get_configured_blog_description( $blog_id, $description = false ) {
		if ( false != $description ) {
			return $description;
		}

		$temp = get_blog_option( $blog_id, 'msls' );
		if ( is_array( $temp ) && empty( $temp['exclude_current_blog'] ) ) {
			return $temp['description'];
		}

		return false;
	}

	/**
	 * Gets the list of the blogs of the reference user
	 * The first available user of the blog will be used if there is no
	 * refrence user configured
	 *
	 * @return array
	 */
	public function get_blogs_of_reference_user( ) {
		$blogs = get_blogs_of_user(
			$this->options->has_value( 'reference_user' ) ?
			$this->options->reference_user :
			current( $this->get_users( 'ID', 1 ) )
		);

		return $blogs;
	}

	/**
	 * Gets blog(s) by language
	 */
	public function get_blog_id( $language ) {
		foreach ( $this->get_objects() as $blog ) {
			if ( $language == $blog->get_language() ) {
				return $blog->userblog_id;
			}
		}

		return null;
	}

	/**
	 * Get the id of the current blog
	 * @return int
	 */
	public function get_current_blog_id() {
		return $this->current_blog_id;
	}

	/**
	 * Checks if current blog is in the collection
	 *
	 * @return bool
	 */
	public function has_current_blog() {
		return ( isset( $this->objects[ $this->current_blog_id ] ) );
	}

	/**
	 * Gets current blog as object
	 * @return Blog|null
	 */
	public function get_current_blog() {
		return (
			$this->has_current_blog() ?
			$this->objects[ $this->current_blog_id ] :
			null
		);
	}

	/**
	 * Gets an array with all blog-objects
	 * @return Blog[]
	 */
	public function get_objects() {
		return $this->objects;
	}

	/**
	 * Is plugin active in the blog with that blog_id
	 *
	 * @param int $blog_id
	 *
	 * @return bool
	 */
	public function is_plugin_active( $blog_id ) {
		if ( ! is_array( $this->active_plugins ) ) {
			$this->active_plugins = get_site_option( 'active_sitewide_plugins', [] );
		}

		if ( isset( $this->active_plugins[ MSLS_PLUGIN_PATH ] ) ) {
			return true;
		}

		$plugins = get_blog_option( $blog_id, 'active_plugins', [] );

		return in_array( MSLS_PLUGIN_PATH, $plugins );
	}

	/**
	 * Gets only blogs where the plugin is active
	 * @return array
	 */
	public function get_plugin_active_blogs() {
		$arr = array();

		foreach ( $this->get_objects() as $id => $blog ) {
			if ( $this->is_plugin_active( $blog->userblog_id ) ) {
				$arr[] = $blog;
			}
		}

		return $arr;
	}

	/**
	 * Gets an array of all - but not the current - blog-objects
	 *
	 * @return array
	 */
	public function get() {
		static $objects = null;

		if ( is_null( $objects ) ) {
			$objects = $this->get_objects();

			if ( $this->has_current_blog() ) {
				unset( $objects[ $this->current_blog_id ] );
			}
		}

		return $objects;
	}

	/**
	 * Gets an array with filtered blog-objects
	 *
	 * @param bool $filter
	 *
	 * @return array
	 */
	public function get_filtered( $filter = false ) {
		if ( ! $filter && $this->current_blog_output ) {
			return $this->get_objects();
		}

		return $this->get();
	}

	/**
	 * Gets the registered users of the current blog
	 *
	 * @param string $fields
	 * @param int|string $number
	 *
	 * @return array
	 */
	public function get_users( $fields = 'all', $number = '' ) {
		$args = array(
			'blog_id' => $this->current_blog_id,
			'orderby' => 'registered',
			'fields'  => $fields,
			'number'  => $number,
		);

		return get_users( $args );
	}

	/**
	 * Gets or creates an instance of BlogCollection
	 *
	 * @return BlogCollection
	 */
	public static function instance() {
		$obj = Registry::get_object( __CLASS__ );

		if ( $obj ) {
			return $obj;
		}

		$options = Options::instance();
		$obj     = new static( $options );

		Registry::set_object( __CLASS__, $obj );

		return $obj;
	}

}
