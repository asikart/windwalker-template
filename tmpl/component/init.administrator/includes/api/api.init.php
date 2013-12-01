<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_flower
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// No direct access
defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;

$view = $input->get('view');
$input->set('view', 'api', 'method', true);
$input->set('format', 'json', 'method', true);

// Replace JDocumentHTML to JSON
JFactory::$document = JDocument::getInstance('json');

include_once dirname(__FILE__) . '/controllerapi.class.php';
include_once dirname(__FILE__) . '/viewapi.class.php';
include_once dirname(__FILE__) . '/errorapi.class.php';

// Replace API Error Handler
ApiError::attachHandler();

// Init API Server
include_once dirname(__FILE__) . '/../core.php';
AKHelper::_('api.initServer');
