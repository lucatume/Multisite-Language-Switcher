<?php
/**
 * OptionsTaxTermCategory
 * @author Dennis Ploetner <re@lloc.de>
 * @since 0.9.8
 */

namespace realloc\Msls;

/**
 * Category options
 * @package Msls
 */
class OptionsTaxTermCategory extends OptionsTaxTerm {

	/**
	 * Base option
	 * @var string
	 */
	protected $base_option = 'category_base';

	/**
	 * Base standard definition
	 * @var string
	 */
	protected $base_defined = 'category';

}
