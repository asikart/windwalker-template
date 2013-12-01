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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
FlowerHelper::_('include.core');


$app = JFactory::getApplication() ;
if( JVERSION >= 3){
    JHtml::_('formbehavior.chosen', 'select');
    if($app->isSite()){
        //FlowerHelper::_('include.fixBootstrapToJoomla');
    }
}else{
    FlowerHelper::_('include.bluestork');
    // FlowerHelper::_('include.fixBootstrapToJoomla');
}



// Init some API objects
// ================================================================================
$date    = JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
$doc     = JFactory::getDocument() ;
$uri     = JFactory::getURI() ;
$user    = JFactory::getUser() ;



// For Site
// ================================================================================
if($app->isSite()) {
    FlowerHelper::_('include.isis');
}



// Edit setting
// ================================================================================
$tabs     = count( $this->fields ) > 1 ? true : false;


if($app->isAdmin()) {
    $span_left  = 8 ;
    $span_right = 4 ;
    
    $width_left = 60 ;
    $width_right= 40 ;
}else{
    $span_left  = 12 ;
    $span_right = 12 ;
    
    $width_left = 100 ;
    $width_right= 100 ;
}

?>
<script type="text/javascript">
    <?php if( $app->isSite() ): ?>
    WindWalker.fixToolbar(0, 300) ;
    <?php endif; ?>
    
    Joomla.submitbutton = function(task)
    {
        if (task == 'sakura.cancel' || document.formvalidator.isValid(document.id('sakura-form'))) {
            Joomla.submitform(task, document.getElementById('sakura-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<div id="Flower" class="windwalker sakura editform <?php echo (JVERSION >= 3) ? 'joomla30' : 'joomla25' ?>">

<form action="<?php echo JRoute::_( JFactory::getURI()->toString() ); ?>" method="post" name="adminForm" id="sakura-form" class="form-validate" enctype="multipart/form-data">    
    
    <!-- Tab Bodys -->
    <?php echo $tabs ? FlowerHelper::_('panel.startTabs', 'sakuraTab', array( 'active' => $this->fields[0] ) ) : null ; ?>
        <?php foreach( $this->fields as $key => $group ): 
                $fieldsets = $this->form->getFieldsets($group) ;
                
                echo $tabs ? FlowerHelper::_('panel.addPanel' , 'sakuraTab', $this->fields_group[$key]['label'] ? $this->fields_group[$key]['label'] : JText::_('COM_FLOWER_EDIT_FIELDS_'.$group) , $group ) : null ;
        ?>
            <div id="group-<?php echo $group; ?>" class="row-fluid">
            
                
                <!-- Left Bar -->
                <div id="group-<?php echo $group; ?>-left" class="span<?php echo $span_left; ?><?php echo JVERSION < 3 ? ' width-'.$width_left : '' ;?> fltlft">
                    
                    <?php foreach( $fieldsets as  $k => $fieldset ): ?>
                        
                        <?php if( empty($fieldset->align) ) $fieldset->align = 'left' ; ?>
                        <?php if( $fieldset->align == 'right' ) continue; ?>
                        
                        <?php
                        // Tabs & Slides
                        $this->startEditFieldsetPanel($fieldset, $fieldsets) ;
                        ?>
                        
                        
                        
                            <!-- Fieldset -->
                            <?php $this->current_group        = $group ?>
                            <?php $this->current_fieldset     = $fieldset; ?>
                            <?php echo $this->loadTemplate('fieldset'); ?>
                        
                        
                        
                        <?php
                        // Tabs & Slides End
                        $this->endEditFieldsetPanel($fieldset) ;
                        ?>
                        
                    <?php endforeach; ?>
                    
                </div>
                
                
                <!-- Right Bar -->
                <div id="group-<?php echo $group; ?>-right" class="span<?php echo $span_right; ?><?php echo JVERSION < 3 ? ' width-'.$width_right : '' ;?> fltlft">
                    
                    
                    <?php foreach( $fieldsets as  $k => $fieldset ): ?>
                        
                        <?php if( empty($fieldset->align) ) $fieldset->align = 'left' ; ?>
                        <?php if( $fieldset->align == 'left' ) continue; ?>
                        
                        <?php
                        // Tabs & Slides
                        $this->startEditFieldsetPanel($fieldset, $fieldsets) ;
                        ?>
                        
                        
                        
                            <!-- Fieldset -->
                            <?php $this->current_group        = $group ?>
                            <?php $this->current_fieldset     = $fieldset; ?>
                            <?php echo $this->loadTemplate('fieldset'); ?>
                        
                        
                        
                        <?php
                        // Tabs & Slides End
                        $this->endEditFieldsetPanel($fieldset) ;
                        ?>
                        
                    <?php endforeach; ?>
                    
                </div>
            
            </div>
            
            <div class="clr"></div>
            
            <?php echo $tabs ? FlowerHelper::_('panel.endPanel' , 'sakuraTab' , $group ) : null ; ?>
            
        <?php endforeach; ?>
        
        <?php echo $tabs ? FlowerHelper::_('panel.endTabs' ) : null ; ?>
    
    
    <!-- Hidden Inputs -->
    <div id="hidden-inputs">
        <input type="hidden" name="option" value="com_flower" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
    <div class="clr"></div>
</form>

</div>