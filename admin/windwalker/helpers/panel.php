<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

 
// no direct access
defined('_JEXEC') or die;


class AKHelperPanel
{
	static public $legacy 	= false ;
	
	static public $buttons 	= array();
	
	static public $script 	= null ;
	
	/*
	 * function startPane
	 * @param 
	 */
	
	public static function startTabs($selector = 'myTab', $params = array())
	{
		if( JVERSION >= 3 ) {
			
			$tab = '' ;
			if(JVERSION < 3.1){
				$tab = '<ul id="'.$selector.'_buttons" class="nav nav-tabs"></ul>' ;
			}
			
			return $tab . JHtml::_('bootstrap.startPane', $selector, $params );
		}else{
			return JHtml::_('tabs.start', $selector, $params);
		}
	}
	
	
	/*
	 * function endTabs
	 * @param 
	 */
	
	public static function endTabs()
	{
		if( JVERSION >= 3 ) {
			
			return JHtml::_('bootstrap.endPane' );
		}else{
			return JHtml::_('tabs.end');
		}
	}
	
	
	/*
	 * function addPane
	 * @param 
	 */
	
	public static function addPanel($selector, $text, $id)
	{
		if( JVERSION >= 3 ) {
			
			if(JVERSION < 3.1){
				self::$buttons[$selector]['text'] = $text ;
				self::$buttons[$selector]['id'] = $id ;
				
				$addclass 	= !self::$script[$selector] ? ",{class: 'active'}" : '';
				//$ul			= !self::$script[$selector] ? "var btns = $('#{$selector}_buttons') ;\n\n" : '';
				
				$sc = self::$script[$selector][] = "jQuery('#{$selector}_buttons').append( jQuery('<li>'{$addclass}).append( jQuery('<a>', {'href': '#{$id}', 'data-toggle': 'tab', text: '{$text}' }) ) );" ;
				echo '<script type="text/javascript">'.$sc.'</script>' ;
			}
			
			return JHtml::_('bootstrap.addPanel', $selector, $id , $text);
		}else{
			return JHtml::_('tabs.panel', $text, $id);
		}
	}
	
	
	/*
	 * function endPanel
	 * @param arg
	 */
	
	public static function endPanel()
	{
		if( JVERSION >= 3 ) {
			return JHtml::_('bootstrap.endPanel' );
		}
	}
	
	
	
	/*
	 * function startSlider
	 * @param $selector
	 */
	
	public static function startSlider($selector = 'mySlider', $params = array())
	{
		if( JVERSION >= 3 ) {
			return JHtml::_('bootstrap.startAccordion', $selector, $params );
		}else{
			return JHtml::_('sliders.start', $selector, $params);
		}
	}
	
	
	/*
	 * function endSlider
	 * @param 
	 */
	
	public static function endSlider()
	{
		if( JVERSION >= 3 ) {
			return JHtml::_('bootstrap.endAccordion' );
		}else{
			return JHtml::_('sliders.end');
		}
	}
	
	
	
	/*
	 * function addSlide
	 * @param 
	 */
	
	public static function addSlide($selector, $text, $id)
	{
		if( JVERSION >= 3 ) {
			return JHtml::_('bootstrap.addSlide', $selector, $text, $id );
		}else{
			return JHtml::_('sliders.panel', $text, $id);
		}
	}
	
	
	/*
	 * function endSlide
	 * @param 
	 */
	
	public static function endSlide()
	{
		if( JVERSION >= 3 ) {
			return JHtml::_('bootstrap.endSlide' );
		}
	}
	
	
	
	
	/*
	 * function setToolbarIcon
	 * @param $class
	 */
	
	public static function setToolbarIcon($image, $default = 'article.png', $path = 'images/admin-icons')
	{
		
	}
	
	
	/*
	 * function setLegacy
	 * @param $conditiotn
	 */
	
	public static function setLegacy($conditiotn = true)
	{
		self::$legacy = $conditiotn;
	}
}
