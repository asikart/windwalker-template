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

/**
 * Main Controller of Flower.
 *
 * @package     Joomla.Site
 * @subpackage  com_flower 
 */
class FlowerController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param    boolean    $cachable     If true, the view output will be cached
     * @param    array      $urlparams    An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return   JController   This object to support chaining.
     * @since    1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        // Load the submenu.
        FlowerHelper::addSubmenu(JRequest::getCmd('view', 'sakuras'));

        $view = JRequest::getCmd('view', 'sakuras');
        JRequest::setVar('view', $view);

        parent::display();

        return $this;
    }
}
