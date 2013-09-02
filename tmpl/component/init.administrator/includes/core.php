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

// Include some system files
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

$app = JFactory::getApplication() ;


// Include WindWalker from libraries or component self.
// ===============================================================
if( !defined('AKPATH_ROOT') ) {
    $inner_ww_path     = JPATH_ADMINISTRATOR . "/components/com_flower/windwalker" ;
    $lib_ww_path    = JPATH_LIBRARIES . '/windwalker' ;
    
    if(file_exists($lib_ww_path.'/init.php')) {
        // From libraries
        $ww_path = $lib_ww_path ;
    }else{
        // From Component folder
        $ww_path = $inner_ww_path ;
    }
    
    
    
    // Init WindWalker
    // ===============================================================
    if(!file_exists($ww_path.'/init.php')) {
        $message = 'Please install WindWalker Framework libraries.' ;
        throw new Exception($message, 500) ;
    }
    include_once $ww_path.'/init.php' ;
}else{
    include_once AKPATH_ROOT.'/init.php' ;
}



// Define
// ========================================================================
define('FLOWER_SITE' , JPATH_ROOT . "/components/com_flower" );
define('FLOWER_ADMIN', JPATH_ADMINISTRATOR . "/components/com_flower");
define('FLOWER_SELF', $app->isAdmin() ? FLOWER_ADMIN : FLOWER_SITE);


// Get Helper
include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_flower/helpers/flower.php" ) ;
include_once JPath::clean( JPATH_ADMINISTRATOR . "/components/com_flower/includes/loader.php" ) ;


// Set Component helper prefix, then AKProxy can use component helper first.
// If component helper and methods not exists, AKProxy will call AKHelper instead.
AKHelper::setPrefix('FlowerHelper') ;
AKHelper::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_flower/helpers');

