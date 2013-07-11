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

jimport('joomla.database.tablenested');

/**
 * Sakura Table class
 *
 * @package     Joomla.Administrator
 * @subpackage  com_flower 
 */
class FlowerTableSakura extends JTable
{
    /**
     * For API Request get SDK id.
     *
     * @var string 
     */
    protected $_option = 'com_flower' ;
    
    /**
     * Constructor
     *
     * @param JDatabase A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__flower_sakuras', 'id', $db);
    }
    
    /**
     * Method to compute the default name of the asset.
     * The default name is in the form table_name.id
     * where id is the value of the primary key of the table.
     *
     * @return  string 
     *
     * @since   11.1
     */
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_flower.sakura.' . (int) $this->$k;
    }
 
    /**
     * Method to return the title to use for the asset table.
     *
     * @return  string 
     *
     * @since   11.1
     */
    protected function _getAssetTitle()
    {
        if( property_exists($this , 'title') && $this->title)
            return $this->title ;
        else
            return $this->_getAssetName() ;
    }
    
    /**
     * Overloaded bind function to pre-process the params.
     *
     * @param    array          Named array
     * @return   null|string    null is operation was satisfactory, otherwise returns an error
     * @see      JTable:bind
     * @since    1.5
     */
    public function bind($array, $ignore = '')
    {
        // for Fields group
        // Convert jform[fields_group][field] to jform[field] or JTable cannot bind data.
        // ==========================================================================================
        $data     = array() ;
        $array     = AKHelper::_('array.pivotFromTwoDimension', $array);
        
        
        
        // Set field['param_xxx'] to params
        // ==========================================================================================
        if(empty($array['params'])){
            $array['params'] = AKHelper::_('array.pivotFromPrefix', 'param_', $array, JArrayHelper::getValue($array, 'params', array())) ;
        }
        
        
        
        // set params to JRegistry
        // ==========================================================================================
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string)$registry;
        }
        
        
        
         // Bind the rules.
         // ==========================================================================================
        if (isset($array['rules']) && is_array($array['rules']))
        {
            $rules = new JAccessRules($array['rules']);
            $this->setRules($rules);
        }
        
        return parent::bind($array, $ignore);
    }
    
    /**
     * Method to perform sanity checks on the JTable instance properties to ensure
     * they are safe to store in the database.  Child classes should override this
     * method to make sure the data they are storing in the database is safe and
     * as expected before storage.
     *
     * @return  boolean  True if the instance is sane and able to be stored in the database.
     *
     * @link    http://docs.joomla.org/JTable/check
     * @since   11.1
     */
    public function check()
    {
        return parent::check();
    }
 
    /**
     * Method to store a row in the database from the JTable instance properties.
     * If a primary key value is set the row with that primary key value will be
     * updated with the instance property values.  If no primary key value is set
     * a new row will be inserted into the database with the properties from the
     * JTable instance.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/store
     * @since   11.1
     */
    public function store($updateNulls = false)
    {
        return parent::store($updateNulls);
    }
    
    /**
     * Method to provide a shortcut to binding, checking and storing a JTable
     * instance to the database table.  The method will check a row in once the
     * data has been stored and if an ordering filter is present will attempt to
     * reorder the table rows based on the filter.  The ordering filter is an instance
     * property name.  The rows that will be reordered are those whose value matches
     * the JTable instance for the property specified.
     *
     * @param   mixed   $src             An associative array or object to bind to the JTable instance.
     * @param   string  $orderingFilter  Filter for the order updating
     * @param   mixed   $ignore          An optional array or space separated list of properties
     *                                    to ignore while binding.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/save
     * @since   11.1
     */
    public function save($src, $orderingFilter = '', $ignore = '')
    {
        return parent::save($src, $orderingFilter, $ignore);
    }
    
    /**
     * Method to delete a row from the database table by primary key value.
     *
     * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/delete
     * @since   11.1
     * @throws  UnexpectedValueException
     */
    public function delete($pk = null, $children = true)
    {
        return parent::delete($pk, $children);
    }
    
    /*
     * Setting Nested table, and rebuild.
     */
    public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
    {
        if(!$parentId){
            // If root not exists, create one.
            $table = JTable::getInstance('Sakura', 'FlowerTable') ;
            if( !$table->load(1) ){
                $k = $this->_tbl_key;
                
                $table->reset();
                $table->$k = 1 ;
                $table->title = 'ROOT' ;
                $table->alias = 'root' ;
                $table->catid = 1 ;
                
                $table->_db->insertObject( $this->_tbl, $table ) ;
                
                $table->reset() ;
                $table->$k = null ;
            }
            
            
            // If cloumn ordering exists, we need clear it, or nested sorting can't work.
            if(property_exists($this, 'ordering')){
                $db = JFactory::getDbo();
                $q = $db->getQuery(true) ;
                
                $q->update($this->_tbl)
                    ->set('ordering = 0')
                    ;
                
                $db->setQuery($q);
                $db->query();
            }
        }
        
        return parent::rebuild($parentId, $leftId, $level, $path);
    }
}
