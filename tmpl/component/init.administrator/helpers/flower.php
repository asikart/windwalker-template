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

include_once dirname(__FILE__).'/../includes/core.php' ;


/**
 * Flower helper.
 */
class FlowerHelper extends AKProxy
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication() ;
		
		if(JVERSION >= 3):
		
			if($app->isAdmin()) {
				JHtmlSidebar::addEntry(
					JText::_('JCATEGORY'),
					'index.php?option=com_categories&extension=com_flower',
					$vName == 'categories'
				);
			}
			
			
			$folders = JFolder::folders(JPATH_ADMINISTRATOR.'/components/com_flower/views');
			
			foreach( $folders as $folder ){
				if( substr($folder, -2) == 'is' || substr($folder, -1) == 's'){
					JHtmlSidebar::addEntry(
						ucfirst($folder) . ' ' . JText::_('COM_FLOWER_TITLE_LIST'),
						'index.php?option=com_flower&view='.$folder,
						$vName == $folder
					);
				}
			}
		
		else:
			
			if($app->isAdmin()) {
				JSubMenuHelper::addEntry(
					JText::_('JCATEGORY'),
					'index.php?option=com_categories&extension=com_flower',
					$vName == 'categories'
				);
			}
			
			$folders = JFolder::folders(JPATH_ADMINISTRATOR.'/components/com_flower/views');
			
			foreach( $folders as $folder ){
				if( substr($folder, -2) == 'is' || substr($folder, -1) == 's'){
					JSubMenuHelper::addEntry(
						ucfirst($folder) . ' ' . JText::_('COM_FLOWER_TITLE_LIST'),
						'index.php?option=com_flower&view='.$folder,
						$vName == $folder
					);
				}
			}
			
		endif;
		
	}
	
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions($option = null)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_flower';

		$actions = array(
			'core.admin', 
			'core.manage', 
			'core.create', 
			'core.edit', 
			'core.edit.own', 
			'core.edit.state', 
			'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	
	/*
	 * function getVersion
	 * @param 
	 */
	
	public static function getVersion()
	{
		return JVERSION ;
	}
	
}
