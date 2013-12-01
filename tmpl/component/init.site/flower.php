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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_flower')) {
    //return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// include helper
include_once JPATH_COMPONENT_ADMINISTRATOR.'/includes/init.php' ;

// Include dependancies
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Flower');
$controller->execute( JFactory::getApplication()->input->get('task') );
$controller->redirect();
