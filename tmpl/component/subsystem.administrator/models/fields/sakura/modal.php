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

include_once JPATH_ADMINISTRATOR.'/components/com_flower/includes/core.php' ;
JForm::addFieldPath( AKPATH_FORM.'/fields');
JFormHelper::loadFieldClass('Modal');

/**
 * Supports a modal picker.
 */
class JFormFieldSakura_Modal extends JFormFieldModal
{
    /**
     * The form field type.
     *
     * @var string
     * @since    1.6
     */
    protected $type = 'Sakura_Modal';
    
    /**
     * List name.
     *
     * @var string 
     */
    protected $view_list = 'sakuras' ;
    
    /**
     * Item name.
     *
     * @var string 
     */
    protected $view_item = 'sakura' ;
    
    /**
     * Extension name, eg: com_content.
     *
     * @var string 
     */
    protected $extension = 'com_flower' ;
    
}
