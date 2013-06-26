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

$published = $this->state->get('filter.a_published');
?>

<?php if( JVERSION >= 3 ): ?>
<div class="modal hide fade" id="batchModal">
	<div class="modal-header">
		<button type="button" role="presentation" class="close" data-dismiss="modal">x</button>
		<h3><?php echo JText::_('COM_FLOWER_BATCH_OPTIONS');?></h3>
	</div>
<?php else: ?>
<fieldset class="batch adminform">
	<legend><?php echo JText::_('COM_FLOWER_BATCH_OPTIONS');?></legend>
	
<?php endif; ?>
	
	<div class="modal-body form-horizontal">
	
		<p><?php echo JText::_('COM_FLOWER_BATCH_TIP'); ?></p>
		
		<?php
			// Remove parent_id in none Nested items.
			if( !$this->state->get('items.nested') ){
				$this->filter['batch']->removeField('parent_id') ;
			}
		?>
		
		<?php foreach( $this->filter['batch']->getFieldset('batch') as $field ): ?>
		<div class="control-group">
			<?php echo $field->label; ?>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php endforeach; ?>

	</div>
	
		
<?php if( JVERSION >= 3 ): ?>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="var inputs = jQuery('#batchModal input, #batchModal select, #batchModal tetarea');inputs.attr('value', '');inputs.trigger('liszt:updated');" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('sakura.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
<?php else: ?>
	<div class="modal-footer">
		<button class="btn"  type="submit" onclick="Joomla.submitbutton('sakura.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
		<button class="btn"  type="button" onclick="var inputs = $$('.batch input, .batch select, .batch tetarea');inputs.set('value', '');">
			<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
		</button>
	</div>
</fieldset>
<?php endif; ?>