<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_flower
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

$doc 	= JFactory::getDocument();
$app 	= JFactory::getApplication();
$lang 	= JFactory::getLanguage();



// Include Helpers
// ========================================================================

// Core init, it can use by module, plugin or other component.
include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_flower/includes/core.php" ) ;

// Set default option to path helper, then AKHelperPath will helpe us get admin path.
AKHelper::_('path.setOption', 'com_flower') ;


// Some useful settings
if( $app->isSite() ){
	
	// Include Admin language as global language.
	$lang->load('', JPATH_ADMINISTRATOR);
	$lang->load('com_flower', JPATH_COMPONENT_ADMINISTRATOR );
	FlowerHelper::_('lang.loadAll', $lang->getTag());
	
	
	// Include Joomla! admin css
	FlowerHelper::_('include.sortedStyle', 'includes/css');
	
	
	// set Base to fix toolbar anchor bug
	$doc->setBase( JFactory::getURI()->toString() );
	
}else{
	FlowerHelper::_('lang.loadAll', $lang->getTag());
	FlowerHelper::_('include.sortedStyle', 'includes/css');
}


// Detect version
FlowerHelper::_('plugin.attachPlugins');

// Debug
define('AKDEBUG', FlowerHelper::_('system.getConfig', 'system.debug', false, 'com_flower')) ;
define('AKDEV', FlowerHelper::_('system.getConfig', 'system.development_mode', true, 'com_flower')) ;

