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

include_once AKPATH_COMPONENT.'/viewlist.php' ;

/**
 * View class for a list of Flower.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_flower 
 */
class FlowerViewSakuras extends AKViewList
{
    /**
     * The prefix to use with controller messages.
     *
     * @var     string
     * @since    1.6
     */
    protected     $text_prefix = 'COM_FLOWER';
    
    /**
     * Item list of table view.
     *
     * @var array   
     */
    protected     $items;
    
    /**
     * Pagination object of table view
     *
     * @var JPagination 
     */
    protected     $pagination;
    
    /**
     * Model state to get some configuration.
     *
     * @var JRegistry 
     */
    protected     $state;
    
    /**
     * The Component option name.
     *
     * @var    string 
     */
    protected    $option       = 'com_flower' ;
    
    /**
     * The URL view list variable.
     *
     * @var    string 
     */
    protected    $list_name    = 'sakuras' ;
    
    /**
     * The URL view item variable.
     *
     * @var    string 
     */
    protected    $item_name    = 'sakura' ;
    
    /**
     * Do not show trash button.
     *
     * @var boolean 
     */
    protected    $no_trash     = false ;
    
    /**
     * Sort fields of table view.
     *
     * @var array   
     */
    protected    $sort_fields ;
    
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication() ;
        
        $this->state        = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');
        $this->filter       = $this->get('Filter');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
        
        parent::displayWithPanel($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since    1.6
     */
    protected function addToolbar()
    {
        // Set title.
        $title = AKDEV
                ? ucfirst($this->getName()) . ' ' . JText::_($this->text_prefix.'_TITLE_LIST')
                : JText::_($this->text_prefix . '_' . strtoupper($this->getName()) . '_TITLE')
                ;
        AKToolBarHelper::title( $title, 'article.png');
        
        parent::addToolbar();
    }
    
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
    protected function getSortFields()
    {
        $ordering_key = $this->state->get('list.orderCol', 'a.ordering') ;
        
        $this->sort_fields = array(
            $ordering_key         => JText::_('JGRID_HEADING_ORDERING'),
            'a.published'         => JText::_('JPUBLISHED'),
            'a.title'             => JText::_('JGLOBAL_TITLE'),
            'b.title'             => JText::_('JCATEGORY'),
            'd.title'             => JText::_('JGRID_HEADING_ACCESS'),
            'a.created_by'        => JText::_('JAUTHOR'),
            'e.title'             => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.created'           => JText::_('JDATE'),
            'a.id'                => JText::_('JGRID_HEADING_ID')
        );
        
        return $this->sort_fields ;
    }
}
