<?php

/*
Plugin Name: Multisite Language Switcher
Plugin URI: http://msls.co/
Description: A simple but powerful plugin that will help you to manage the relations of your contents in a multilingual multisite-installation.
Version: 2.0
Author: Dennis Ploetner
Author URI: http://lloc.de/
Text Domain: multisite-language-switcher
*/

/*
Copyright 2013  Dennis Ploetner  (email : re@lloc.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * MultisiteLanguageSwitcher
 *
 * @author Dennis Ploetner <re@lloc.de>
 */
if ( ! defined( 'MSLS_PLUGIN_VERSION' ) ) {
	define( 'MSLS_PLUGIN_VERSION', '2.0' );

	if ( ! defined( 'MSLS_PLUGIN_PATH' ) ) {
		define( 'MSLS_PLUGIN_PATH', ( function_exists( 'plugin_dir_path' ) ? plugin_dir_path( __FILE__ ) : __DIR__ . '/' ) );
	}

	if ( ! defined( 'MSLS_PLUGIN__FILE__' ) ) {
		define( 'MSLS_PLUGIN__FILE__', __FILE__ );
	}

	\realloc\Msls\Plugin::init();

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'Admin', 'init' ) );

			add_action( 'load-post.php', array( 'MetaBox', 'init' ) );

			add_action( 'load-post-new.php', array( 'MetaBox', 'init' ) );

			add_action( 'load-edit.php', array( 'MslsCustomColumn', 'init' ) );
			add_action( 'load-edit.php', array( 'CustomFilter', 'init' ) );

			add_action( 'load-edit-tags.php', array( 'MslsCustomColumnTaxonomy', 'init' ) );
			add_action( 'load-edit-tags.php', array( 'PostTag', 'init' ) );

			if ( filter_has_var( INPUT_POST, 'action' ) ) {
				$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

				if ( 'add-tag' == $action ) {
					add_action( 'admin_init', array( 'PostTag', 'init' ) );
				}
				elseif ( 'inline-save' == $action ) {
					add_action( 'admin_init', array( 'MslsCustomColumn', 'init' ) );
				}
				elseif ( 'inline-save-tax' == $action ) {
					add_action( 'admin_init', array( 'MslsCustomColumnTaxonomy', 'init' ) );
				}
			}

			add_action( 'wp_ajax_suggest_posts', array( 'MetaBox', 'suggest' ) );

			add_action( 'wp_ajax_suggest_terms', array( 'PostTag', 'suggest' ) );
		}

		/**
		 * Filter for the_content()
		 *
		 * @package Msls
		 * @uses Options
		 * @param string $content
		 * @return string
		 */
		function msls_content_filter( $content ) {
			if ( ! is_front_page() && is_singular() ) {
				$options = realloc\Msls\Options::instance();

				if ( $options->is_content_filter() ) {
					$content .= msls_filter_string();
				}
			}
			return $content;
		}

		add_filter( 'the_content', 'msls_content_filter' );

		/**
		 * Create filterstring for msls_content_filter()
		 *
		 * @package Msls
		 * @uses Output
		 * @param string $pref
		 * @param string $post
		 * @return string
		 */
		function msls_filter_string( $pref = '<p id="msls">', $post = '</p>' ) {
			$options    = realloc\Msls\Options::instance();
			$collection = realloc\Msls\BlogCollection::instance();
			$obj        = new realloc\Msls\Output( $options, $collection );
			$links      = $obj->get( 1, true, true );
			$output     = __( 'This post is also available in %s.', 'multisite-language-switcher' );

			if ( has_filter( 'msls_filter_string' ) ) {
				/**
				 * Overrides the string for the output of the translation hint
				 * @since 1.0
				 * @param string $output
				 * @param array $links
				 */
				$output = apply_filters( 'msls_filter_string', $output, $links );
			}
			else {
				if ( count( $links ) > 1 ) {
					$last   = array_pop( $links );
					$output = sprintf(
						$output,
						sprintf(
							__( '%s and %s', 'multisite-language-switcher' ),
							implode( ', ', $links ),
							$last
						)
					);
				}
				elseif ( 1 == count( $links ) ) {
					$output = sprintf(
						$output,
						$links[0]
					);
				}
				else {
					$output = '';
				}
			}
			return( ! empty( $output ) ? $pref . $output . $post : '' );
		}

		/**
		 * Get the output for using the links to the translations in your code
		 *
		 * @package Msls
		 * @param array $arr
		 * @return string
		 */
		function get_the_msls( $arr = array() ) {
			$obj = realloc\Msls\Output::init()->set_tags( (array) $arr );

			return( sprintf( '%s', $obj ) );
		}

		add_shortcode( 'sc_msls', 'get_the_msls' );

		/**
		 * Output the links to the translations in your template
		 *
		 * You can call this function directly like that
		 *
		 *     if ( function_exists ( 'the_msls' ) )
		 *         the_msls();
		 *
		 * or just use it as shortcode [sc_msls]
		 *
		 * @package Msls
 	 	 * @uses get_the_msls
		 * @param array $arr
		 */
		function the_msls( $arr = array() ) {
			echo get_the_msls( $arr );
		}

		/**
		 * Help searchengines to index and to serve the localized version with
		 * rel="alternate"-links in the html-header
		 */
		function msls_head() {
			$mydata = realloc\Msls\Options::create();
			$blogs  = realloc\Msls\BlogCollection::instance();

			$current_blog = $blogs->get_current_blog_id();
			foreach ( $blogs->get_objects() as $blog ) {
				$language = $blog->get_language();
				$url      = $mydata->get_current_link();
				$title    = $blog->get_description();

				if ( $blog->userblog_id != $current_blog ) {
					switch_to_blog( $blog->userblog_id );

					if ( 'realloc\Msls\Options' != get_class( $mydata ) && ( is_null( $mydata ) || ! $mydata->has_value( $language ) ) ) {
						restore_current_blog();
						continue;
					}

					$url   = $mydata->get_permalink( $language );
					$title = $blog->get_description();

					restore_current_blog();
				}

				if ( has_filter( 'msls_head_hreflang' ) ) {
					/**
					 * Overrides the hreflang value
					 * @since 0.9.9
					 * @param string $language
					 */
					$hreflang = (string) apply_filters( 'msls_head_hreflang', $language );
				}
				else {
					$hreflang = $blog->get_alpha2();
				}

				$output = sprintf(
					'<link rel="alternate" hreflang="%s" href="%s" title="%s" />',
					$hreflang,
					$url,
					esc_attr( $title )
				);

				echo $output, "\n";
			}
		}

		add_action( 'wp_head', 'msls_head' );

	}
	else {

		/**
		 * Prints a message that the Multisite Language Switcher needs an
		 * active multisite to work properly.
		 */
		function plugin_needs_multisite() {
			realloc\Msls\Plugin::message_handler(
				__( 'The Multisite Language Switcher needs the activation of the multisite-feature for working properly. Please read <a onclick="window.open(this.href); return false;" href="http://codex.wordpress.org/Create_A_Network">this post</a> if you don\'t know the meaning.', 'multisite-language-switcher' )
			);
		}
		add_action( 'admin_notices', 'plugin_needs_multisite' );

	}
}
