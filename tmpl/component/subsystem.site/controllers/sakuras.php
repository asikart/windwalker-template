<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_flower
 * @author      Simon ASika <asika32764@gmail.com>
 * @copyright   Copyright (C) 2013 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

include_once AKPATH_COMPONENT . '/controlleradmin.php';

/**
 * Sakuras list controller class.
 *
 * @package     Joomla.Site
 * @subpackage  com_flower
 */
class FlowerControllerSakuras extends AKControllerAdmin
{
	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 */
	protected $view_list = 'sakuras';

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 */
	protected $view_item = 'sakura';

	/**
	 * The Component name.
	 *
	 * @var    string
	 */
	protected $component = 'flower';

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @see     JController
	 *
	 * @since   11.1
	 */
	public function __construct($config = array())
	{
		$this->redirect_tasks = array(
			'save', 'cancel', 'publish', 'unpublish', 'delete'
		);

		parent::__construct($config);
	}
}