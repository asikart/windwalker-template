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

jimport('joomla.application.component.view');

class AKViewApi extends JViewLegacy
{

	public $_buffer ;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$doc 	= JFactory::getDocument();
		$class	= JRequest::getVar('class') ;
		$method = JRequest::getVar('method');
		
		
		if( !$class ){
			ApiError::raiseError(404, 'Class not found.') ;
		}
		
		
		// Handle Params
		// =================================================================
		if(!$method){
			$method = 'getItems' ;
		}
		
		if(!JRequest::getVar('id')) {
			JRequest::setVar('id', 1, 'method', true) ;
		}
		
		
		
		// Session
		// =================================================================
		//$this->handleSession() ;
		
		
		
		// Excute method
		// =================================================================
		$ctrl 	= JControllerLegacy::getInstance('Flower');
		$model 	= $ctrl->getModel($class) ;
		$data 	= $model->$method() ;
		
		
		
		// Check for errors.
		// =================================================================
		if (count($errors = $this->get('Errors'))) {
			ApiError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		
		
		// render Result
		// =================================================================
		$result = new JObject();
		$result->set( 'ApiResult', $data ) ;
		
		if(JDEBUG){
			$result->set( 'debug' , ApiError::$debug ) ;
		}
		
		echo $json =  json_encode($result);
		return true ;
	}
	
	
	/*
	 * function setBuffer
	 * @param $data
	 */
	
	public function setBuffer($data)
	{
		$this->_buffer = $data ;
	}
	
	
	/*
	 * function getBuffer
	 * @param 
	 */
	
	public function getBuffer()
	{
		return $this->_buffer ;
	}
	
	
	/*
	 * function handleSession
	 * @param 
	 */
	
	public function handleSession()
	{
		$class = JRequest::getVar('class') ;
		
		// get session key and detete user
		// =================================================================
		$s 		= JFactory::getSession();
		
		$key 	=  JRequest::getVar('session_key') ;
		$db 	= JFactory::getDbo();
		$q 		= $db->getQuery(true);
		$q->select('userid')
			->from('#__session')
			->where("session_id='{$key}'")
			;
			
		$db->setQuery($q,0,1);
		$uid = $db->loadResult();
		
		
		
		// if user has loged in, set it in session.
		// =================================================================
		if( $uid ){
			$user = JFactory::getUser($uid);
			$s->set('user',$user);
		}
		
		
		
		// Detect is login?
		// =================================================================
		$user = JFactory::getUser();
		if ( $user->guest && $class != 'user' ) {
			ApiError::raiseError(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	}
}