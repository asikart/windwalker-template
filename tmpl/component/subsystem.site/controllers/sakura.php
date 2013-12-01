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


include_once AKPATH_COMPONENT.'/controllerform.php' ;

/**
 * Sakura controllerform class to edit item.
 *
 * @package     Joomla.Site
 * @subpackage  com_flower 
 */
class FlowerControllerSakura extends AKControllerForm
{
    /**
     * The URL view list variable.
     *
     * @var    string 
     */
    protected $view_list = 'sakuras' ;
    
    /**
     * The URL view item variable.
     *
     * @var    string 
     */
    protected $view_item = 'sakura' ;
    
    /**
     * The Component name.
     *
     * @var    string 
     */
    protected $component = 'flower';
    
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   11.1
     */
    
    function __construct() {
        
        $this->allow_url_params = array(
            'return'
        );
        
        $this->redirect_tasks = array(
            'save', 'cancel', 'publish', 'unpublish', 'delete'
        );
        
        parent::__construct();
    }
    
    /**
     * Function that allows child controller access to model data
     * after the data has been saved.
     *
     * @param   JModel  &$model     The data model object.
     * @param   array   $validData  The validated data.
     *
     * @return  void 
     *
     * @since   11.1
     */
    protected function postSaveHook( &$model, $validData = array())
    {
        $result = $model->postSaveHook($validData) ;
        return $result ;
    }
}