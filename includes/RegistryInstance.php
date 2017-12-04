<?php
/**
 * RegistryInstance
 *
 * @author Dennis Ploetner <re@lloc.de>
 * @since 2.0
 */

namespace realloc\Msls;

/**
 * Interface for classes which are to register in the MslsRegistry-instance
 *
 * get_called_class is just avalable in php >= 5.3 so I defined an interface here
 * @package Msls
 */
interface RegistryInstance {

	/**
	 * Returnse an instance
	 * @return object
	 */
	public static function instance();

}