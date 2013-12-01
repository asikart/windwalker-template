<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_flower
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// No direct access
defined('_JEXEC') or die;


jimport('joomla.application.component.view');

if(!class_exists('AKViewApi')){
    flowerLoader('admin://includes/api/api.init');
}

/**
 * View for API System
 */
class FlowerViewApi extends AKViewApi
{
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        parent::display($tpl);
    }
}
